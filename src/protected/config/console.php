<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return [
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Console Application',

    // preloading 'log' component
    'preload'=>['log'],

    'import'=>[
        'application.components.*',
        'application.models.*',
        'ext.giix-components.*',
    ],
    // application components
    'components'=>[
        'db' => [
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
        ],
        'log' => [
            'class'=>'CLogRouter',
            'routes'=>[
                [
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ],
            ],
        ],
    ],
];