<?php
use common\models\User;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute'=>'user/index',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'assetManager' => [
            'linkAssets' => true,  // create symlinks of assets
        ],

        'user' => [
            'class'           => 'yii\web\User',
            'identityClass'   => 'common\models\User',
            'loginUrl'        => ['sign-in/login'],
            'enableAutoLogin' => true,
        ],
        /*
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        */
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'as globalAccess'=>[
        'class'=>'\common\components\behaviors\GlobalAccessBehavior',
        'denyCallback' => function($rule, $action){
            if (Yii::$app->user->getIsGuest()) {
                Yii::$app->user->loginRequired();
            } else {
                echo Yii::t('yii', 'You are not allowed to perform this action.');
                Yii::$app->response->statusCode = 403;
                Yii::$app->end();
            }
        },
        'rules'=>[
            [
                'controllers'=>['sign-in'],
                'allow' => true,
                'roles' => ['?'],
                'actions'=>['login']
            ],
            [
                'controllers'=>['site'],
                'allow' => true,
                'roles' => ['?','@'],
                'actions'=>['error']
            ],
            [
                'controllers'=>['debug/default'],
                'allow' => true,
                'roles' => ['?','@'],
            ],
            [
                'controllers'=>['user'],
                'allow' => true,
                'roles' => [User::ROLE_ADMIN],
            ],
            [
                'controllers'=>['user'],
                'allow' => false,
            ],
            [
                'allow' => true,
                'roles' => [User::ROLE_MANAGER],
            ]
        ]
    ],
    'params' => $params,
];
