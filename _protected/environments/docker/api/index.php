<?php

defined('YII_DEBUG') or define('YII_DEBUG', getenv('PROJECT_DEBUG'));
defined('YII_ENV') or define('YII_ENV', getenv('PROJECT_ENV'));

error_reporting(E_ALL ^E_NOTICE);


require(__DIR__ . '/../_protected/vendor/autoload.php');
require(__DIR__ . '/../_protected/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../_protected/common/config/bootstrap.php');
require(__DIR__ . '/../_protected/api/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../_protected/common/config/main.php'),
    require(__DIR__ . '/../_protected/common/config/main-local.php'),
    require(__DIR__ . '/../_protected/api/config/main.php'),
    require(__DIR__ . '/../_protected/api/config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();
