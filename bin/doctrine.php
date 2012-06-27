<?php
use Zend\ServiceManager\ServiceManager;
/**
 * Before running of this script variable $serviceManager should be set
 * 
 * @var ServiceManager $serviceManager
 */

$serviceManager =isset($serviceManager)?$serviceManager:NULL;

ini_set('display_errors', true);

if (!isset($serviceManager)) {
	throw new RuntimeException('Service manager not set');
} elseif (!$serviceManager instanceof ServiceManager) {
	throw new RuntimeException('Service manager registered but not instance of ServiceManager');
}

$serviceManager->get('Application')->bootstrap();
$serviceManager->get('doctrine.cli')->run();