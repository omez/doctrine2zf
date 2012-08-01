<?php

use Zend\ServiceManager\ServiceManager;
use Symfony\Component\Console\Input\ArgvInput;

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


/* @var $cli \Symfony\Component\Console\Application */
$cli = $serviceManager->get('doctrine.cli');


// registering migrations.xml to all migrations:* commands
var_dump($cli->all('migrations'));

/*$input = new ArgvInput();
if (!$input->getOption('configuration')) {
	$input->setOption('configuration', 'config/migrations.xml');
}*/

$cli->run($input);

