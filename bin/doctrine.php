<?php
/**
 * Before running of this script variable $serviceManager should be set
 * 
 * 
 * @var Zend\ServiceManager\ServiceManager $serviceManager
 */
$serviceManager;

use Zend\Mvc\Service\ServiceManagerConfiguration;

ini_set('display_errors', true);

if (isset($serviceManager) || !$serviceManager instanceof ServiceManager) {
	throw new RuntimeException("Script is unable to find service manager instance");
}

$serviceManager->get('Application')->bootstrap();
$serviceManager->get('doctrine.cli')->run();

