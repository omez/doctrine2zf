<?php
namespace Doctrine2zf;

use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Loader\ClassMapAutoloader;

/**
 * Doctrine2zf module
 * 
 * @author Alexander Sergeychik
 */
class Module implements InitProviderInterface, ConfigProviderInterface, AutoloaderProviderInterface {
	
	protected $_submodules = array(
		'DoctrineModule' => array(
			'classname' => 'DoctrineModule\\Module',
			'path' => 'submodules/DoctrineModule/Module.php',
		),
		'DoctrineORMModule' => array(
			'classname' => 'DoctrineORMModule\\Module',
			'path' => 'submodules/DoctrineORMModule/Module.php',
		),
	);
	
	/**
	 * {@inheritDoc}
	 */
	public function init(ModuleManagerInterface $manager) {
		
		$modules = $manager->getModules();
		if (!is_array($modules) || !$modules instanceof \ArrayAccess) {
			throw new \RuntimeException('Registering modules currently supports only arrays or \ArrayAccess instances');
		}
		
		foreach ((array)$this->_submodules as $submodule=>$data) {
			$modules[] = $submodule;
		}
		
		$modules[] = $name;
		$manager->setModules($modules);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getConfig() {
		// returns merged configuration
		return include __DIR__.'/config/module.config.php';		
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getAutoloaderConfig() {
		
		// Registering submodules
		$map = array();
		foreach ((array)$this->_submodules as $submodule=>$data) {
			$map[$data['classname']] = __DIR__. DIRECTORY_SEPARATOR . $data['path'];
		}
		
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					'DoctrineTool' => __DIR__ . '/src/DoctrineTool',
				),
			),
			'Zend\Loader\ClassMapAutoloader' => array(
				'map'=> $map,
			),
		);
	}
	
}