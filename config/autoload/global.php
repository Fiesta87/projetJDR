<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn'    => 'mysql:dbname=wesh;host=localhost;charset=utf8;',
        'username' => 'root', 
        'password' => '', 
    ],
    'service_manager' => [ 
        'factories' => [  
           'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ], 
    ],
    'session_config' => [
        'cookie_lifetime'     => 60*60*1,
        'gc_maxlifetime'      => 60*60*24*30,    
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
];
