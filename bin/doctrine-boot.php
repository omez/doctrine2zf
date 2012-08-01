<?php
namespace Doctrine2zf;

use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceManager;


if (!function_exists('lookForBasePath')) {
	function lookForBasePath($path, $from = __DIR__) {
		$previousFrom = $from;
		while (true) {
			$fullpath = $from . DIRECTORY_SEPARATOR . $path;
			if (file_exists($fullpath)) {
				return $from;
			} else {
				$previousFrom = $from;
				$from = $from.'/..';
				if (realpath($previousFrom) === realpath($from)) {
					return false;
				}
			}
		}
	}
}


$basePath = lookForBasePath('boot/boot.php');
if ($basePath !== false) {
	chdir($basePath);
	$serviceManager = require 'boot/boot.php';
	if (!$serviceManager instanceof ServiceManager) {
		throw new \RuntimeException('Service manager not set');
	}
	$serviceManager->get('Application')->bootstrap();
	return $serviceManager;
}


$basePath = lookfor('config/application.config.php');
if ($basePath !== false) {
	chdir($basePath);
	
	// looking for application vendor autoloader 
	if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
		throw new \RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
	}
	
	$application = Application::init(include 'config/application.config.php');
	$serviceManager = $application->getServiceManager();
	return $serviceManager;
}

// if no return we have failed 
throw new \RuntimeException("Unable to bootstrap doctrine application");