<?php

// change the following paths if necessary
$yii = dirname(__FILE__) . '/protected/vendor/yiisoft/yii/framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// enable PHP error reporting, remove in production
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');
error_reporting(-1);

$files = array_merge(
    glob(dirname(__FILE__) . '/protected/vendor/fufu70/reflection-class/src/*.php'),
    glob(dirname(__FILE__) . '/protected/vendor/fufu70/file-class/src/*.php'),
    glob(dirname(__FILE__) . '/protected/vendor/fufu70/file-class/src/file/*.php'),
    glob(dirname(__FILE__) . '/protected/vendor/milf/common-php/src/*.php'),
    glob(dirname(__FILE__) . '/protected/vendor/milf/asset-library/src/*.php'),
    glob(dirname(__FILE__) . '/protected/vendor/milf/asset-library/src/action/*.php'),
    glob(dirname(__FILE__) . '/protected/vendor/milf/asset-library/src/file/*.php')
);

require_once($yii);

foreach ($files as $file) {
    require_once($file);
}

Yii::createWebApplication($config)->run();