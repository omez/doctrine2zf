<?php
// Main Doctrine2zf configuration
// NOTE: this file was automatically created by builder

return array(
	'doctrine' => array(
		'cache' => array(
			'apc' => array(
				'class' => 'Doctrine\\Common\\Cache\\ApcCache',
			),
			'array' => array(
				'class' => 'Doctrine\\Common\\Cache\\ArrayCache',
			),
			'memcache' => array(
				'class' => 'Doctrine\\Common\\Cache\\Memcache',
				'instance' => 'my_memcache_alias',
			),
			'memcached' => array(
				'class' => 'Doctrine\\Common\\Cache\\Memcached',
				'instance' => 'my_memcached_alias',
			),
			'redis' => array(
				'class' => 'Doctrine\\Common\\Cache\\RedisCache',
				'instance' => 'my_redis_alias',
			),
			'wincache' => array(
				'class' => 'Doctrine\\Common\\Cache\\Wincache',
			),
			'xcache' => array(
				'class' => 'Doctrine\\Common\\Cache\\XcacheCache',
			),
			'zenddata' => array(
				'class' => 'Doctrine\\Common\\Cache\\ZendDataCache',
			),
		),
		'orm_autoload_annotations' => true,
		'connection' => array(
			'orm_default' => array(
				'configuration' => 'orm_default',
				'eventmanager' => 'orm_default',
				'params' => array(
					'host' => 'localhost',
					'port' => '3306',
					'user' => 'username',
					'password' => 'password',
					'dbname' => 'database',
				),
			),
		),
		'configuration' => array(
			'orm_default' => array(
				'metadata_cache' => 'array',
				'query_cache' => 'array',
				'result_cache' => 'array',
				'driver' => 'orm_default',
				'generate_proxies' => true,
				'proxy_dir' => 'data/proxies',
				'proxy_namespace' => 'DoctrineProxy\\Proxy',
			),
		),
		'driver' => array(
			'orm_default' => array(
				'class' => 'Doctrine\\ORM\\Mapping\\Driver\\DriverChain',
				'drivers' => array(
				),
			),
		),
		'entitymanager' => array(
			'orm_default' => array(
				'connection' => 'orm_default',
				'configuration' => 'orm_default',
			),
		),
		'eventmanager' => array(
			'orm_default' => array(
			),
		),
	),
);
