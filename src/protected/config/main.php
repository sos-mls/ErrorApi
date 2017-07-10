<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',

    'name' => 'ErrorApi',

    // preloading 'log' component
    'preload' => ['log'],

    // autoloading model and component classes
    'import' => [
        'application.models.*',
        'application.components.*',
        'application.controllers.*',
        'ext.giix-components.*',
    ],

    'modules' => [
        // Gii tool should be removed in production
        'gii' => [
            'class' => 'system.gii.GiiModule',
            'password' => 'here',
            'ipFilters' => ['127.0.0.1','192.168.201.*', '10.0.2.*'],
            'generatorPaths' => [
                'ext.giix-core', // giix generators
            ],
        ],
    ],

    'defaultController' => 'default',
    // application components
    'components' => [
        'urlManager' => [
            'class' => 'Common\UrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            // 'caseSensitive' => false,
            'rules' => [
                // REST patterns
                ['api/list', 'pattern' => 'api/<model:\w+>', 'verb' => 'GET'],
                ['api/view', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'GET'],
                ['api/update', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'PUT'],
                ['api/delete', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'DELETE'],
                ['api/create', 'pattern' => 'api/<model:\w+>', 'verb' => 'POST'],
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'db' => [
            'connectionString' => 'mysql:host=localhost;dbname=ErrorAPIDB',
            'username' => isset($_SERVER['errorapi_mysql_username']) ? $_SERVER['errorapi_mysql_username'] : 'root',
            'password' => isset($_SERVER['errorapi_mysql_password']) ? $_SERVER['errorapi_mysql_password'] : 'default_password',
            'emulatePrepare' => true,
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_',
        ],
        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ]
            ],
        ],
        'hash' => ['class' => 'Common\PBKDF2Hash'],
        'random' => ['class' => 'Common\RandomString']
    ],

    // application-level parameters that can be accessed using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
    
    'controllerMap' => [
        'default' => 'application.controllers.ErrorController',
    ],
];
