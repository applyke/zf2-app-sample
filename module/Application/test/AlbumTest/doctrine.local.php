<?php


return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => '123',
                    'dbname' => 'galleryDBTest',
                    'charset' => 'UTF8'
                )
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                //'metadata_cache' => 'filesystem',
                //'query_cache' => 'filesystem',
                'metadata_cache' => 'array',
                'query_cache' => 'array',
                'result_cache' => 'array',
                'hydration_cache' => 'array',
                'generate_proxies' => true,
            )
        ),
    ),
 );
