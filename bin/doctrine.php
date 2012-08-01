<?php
use Zend\ServiceManager\ServiceManager;

ini_set('display_errors', true);

/**
 * Before running of this script variable $serviceManager should be set
 *
 * @var $serviceManager $serviceManager
 */
$serviceManager = include __DIR__.'/doctrine-boot.php';

if (!isset($serviceManager)) {
	throw new RuntimeException('Service manager not set');
} elseif (!$serviceManager instanceof ServiceManager) {
	throw new RuntimeException('Service manager registered but not instance of ServiceManager');
}

$serviceManager->get('doctrine.cli')->run();