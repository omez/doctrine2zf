<?php
namespace Doctrine2zf;

use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Doctrine2zf module
 * 
 * @author Alexander Sergeychik
 */
class Module implements InitProviderInterface, ConfigProviderInterface, AutoloaderProviderInterface {
	
	protected $_submodules = array(
		'DoctrineModule' => 'build/submodules/DoctrineModule',
		'DoctrineORMModule' => 'build/submodules/DoctrineORMModule',
	);
	
	/**
	 * {@inheritDoc}
	 */
	public function init(ModuleManagerInterface $manager) {
		
		// Registering submodules
		foreach ((array)$this->_submodules as $submodule) {
			$this->_registerSubmodule($submodule);
		}
		
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
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					'DoctrineTool' => __DIR__ . '/src/DoctrineTool',
				),
			),
		);
	}
	
	
	protected function _registerSubmodule($submodule) {
		// TODO registration process
	}
	
}