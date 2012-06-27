<?php
namespace Doctrine2zf;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use DoctrineModule\Service as CommonService;
use DoctrineORMModule\Service as ORMService;
use DoctrineModule\Service\CacheFactory;

/**
 * Doctrine2zf module
 * 
 * @author Alexander Sergeychik
 */
class Module implements InitProviderInterface, ConfigProviderInterface, ServiceProviderInterface, AutoloaderProviderInterface {
	
	const DEFAULT_ENTITYMANAGER_ALIAS = 'orm_default';
	const DEFAULT_CONNECTION_ALIAS = 'orm_default';
	const DEFAULT_CONFIGURATION_ALIAS = 'orm_default';
	const DEFAULT_DRIVER_ALIAS = 'orm_default';
	const DEFAULT_EVENTMANAGER_ALIAS = 'orm_default';
	
	
	public function init(ModuleManagerInterface $mm)
	{
		$mm->events()->attach('loadModules.post', function($e) {
			$config   = $e->getConfigListener()->getMergedConfig();
			$autoload = isset($config['doctrine']['orm_autoload_annotations'])
				? $config['doctrine']['orm_autoload_annotations']
				: false;
	
			if ($autoload) {
				$refl = new \ReflectionClass('Doctrine\ORM\Mapping\Driver\AnnotationDriver');
				$path = realpath(dirname($refl->getFileName())) . '/DoctrineAnnotations.php';
	
				AnnotationRegistry::registerFile($path);
			}
		});
	}
	
	public function getConfig() {
		return include __DIR__.'/config/module.config.php';		
	}
	
	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					'DoctrineModule' => __DIR__ . '/src/DoctrineModule',
					'DoctrineORMModule' => __DIR__ . '/src/DoctrineORMModule',
					'DoctrineTool' => __DIR__ . '/src/DoctrineTool',
				),
			),
		);
	}
	
	public function onBootstrap(EventInterface $e) {
		$this->onBootstrapCli($e);
		$this->onBootstrapInjections($e);
	}
	
	public function onBootstrapCli(EventInterface $e) {
		$app	= $e->getTarget();
		$events = $app->events()->getSharedManager();
	
		// Attach to helper set event and load the entity manager helper.
		$events->attach('doctrine', 'loadCli.post', function($e) {
			$cli = $e->getTarget();
	
			$cli->addCommands(array(
				// DBAL Commands
				new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
				new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),
	
				// ORM Commands
				new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
				new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
				new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
				new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
				new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
				new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
				new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
				new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
				new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
				new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
				new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
				new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
				
				// Schema tool
				new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
				new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
				new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
				
				// Migrations tools
				new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
				new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
				new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
				new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
				new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
				new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand(),
				
			));
	
			$em = $e->getParam('ServiceManager')->get('doctrine.entitymanager.orm_default');
			$db = new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection());
			$eh = new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em);
			$cli->getHelperSet()->set($db, 'db');
			$cli->getHelperSet()->set($eh, 'em');
		});
	}
	
	/**
	 * Adds controller support for doctrine EM
	 * 
	 * @see https://github.com/doctrine/DoctrineORMModule#injection
	 * 
	 * @return Event
	 * @return void
	 */
	public function onBootstrapInjections(EventInterface $e) {
		
		$application = $e->getApplication();
		$serviceManager = $application->getServiceManager();
		
		$controllerLoader = $serviceManager->get('ControllerLoader');
		
		// Add initializer to Controller Service Manager that check if controllers needs entity manager injection
		$controllerLoader->addInitializer(function ($instance) use ($serviceManager) {
			if (method_exists($instance, 'setEntityManager')) {
				$instance->setEntityManager($serviceManager->get('doctrine.entitymanager.orm_default'), 'orm_default');
			}
		});
	}
	
	/**
	 * Expected to return \Zend\ServiceManager\Configuration object or array to
	 * seed such an object.
	 *
	 * @return array|\Zend\ServiceManager\Configuration
	 */
	public function getServiceConfiguration()
	{
		return array(
			
			'aliases' => array(
				'Doctrine\ORM\EntityManager' => 'doctrine.entitymanager.orm_default',
			),
			
			'factories' => array(
				'DoctrineORMModule\Form\Annotation\AnnotationBuilder' => function($sm) {
					return new \DoctrineORMModule\Form\Annotation\AnnotationBuilder(
							$sm->get('doctrine.entitymanager.orm_default')
					);
				},
				'doctrine.connection.orm_default'	=> new CommonService\ConnectionFactory('orm_default'),
				'doctrine.configuration.orm_default' => new ORMService\ConfigurationFactory('orm_default'),
				'doctrine.driver.orm_default'		=> new CommonService\DriverFactory('orm_default'),
				'doctrine.entitymanager.orm_default' => new ORMService\EntityManagerFactory('orm_default'),
				'doctrine.eventmanager.orm_default'  => new CommonService\EventManagerFactory('orm_default'),
				
				
				'doctrine.cli'			 => 'DoctrineModule\Service\CliFactory',
				'doctrine.cache.apc'	   => new CacheFactory('apc'),
				'doctrine.cache.array'	 => new CacheFactory('array'),
				'doctrine.cache.memcache'  => new CacheFactory('memcache'),
				'doctrine.cache.memcached' => new CacheFactory('memcached'),
				'doctrine.cache.redis'	 => new CacheFactory('redis'),
				'doctrine.cache.wincache'  => new CacheFactory('wincache'),
				'doctrine.cache.xcache'	=> new CacheFactory('xcache'),
				'doctrine.cache.zenddata'  => new CacheFactory('zenddata'),
			),
		);
	}
	
}