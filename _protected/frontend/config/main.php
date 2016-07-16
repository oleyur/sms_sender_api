<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'user' => [
            'class' => 'frontend\modules\user\Module',
        ],
    ],
    'components' => [
        // here you can set theme used for your frontend application 
        'view' => [
            'theme' => [
//                'pathMap' => ['@app/views' => '@webroot/themes/cerulean/views'],
//                'baseUrl' => '@web/themes/cerulean',
                'pathMap' => ['@app/views' => '@webroot/themes/advanced/views'],
                'baseUrl' => '@web/themes/advanced',
            ],
        ],

        'assetManager' => [
            'linkAssets' => true,  // create symlinks of assets

            'bundles' => [
                // we will use bootstrap css from our theme
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [], // do not use yii default one
                ],
                // // use bootstrap js from CDN
                // 'yii\bootstrap\BootstrapPluginAsset' => [
                //     'sourcePath' => null,   // do not use file from our server
                //     'js' => [
                //         'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js']
                // ],
                // // use jquery from CDN
                // 'yii\web\JqueryAsset' => [
                //     'sourcePath' => null,   // do not use file from our server
                //     'js' => [
                //         '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
                //     ]
                // ],
            ],
        ],
        /*
        'user' => [
            'identityClass' => 'common\models\UserIdentity',
            'enableAutoLogin' => true,
        ],
        */
        'user' => [
            'class'=>'yii\web\User',
            'identityClass' => 'common\models\User',
            'loginUrl'=>['/user/sign-in/login'],
            'enableAutoLogin' => true,
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
//                'github' => [
//                    'class' => 'yii\authclient\clients\GitHub',
//                    'clientId' => 'your-client-id',
//                    'clientSecret' => 'your-client-secret',
//                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => 'your-client-id',
                    'clientSecret' => 'your-client-secret',
                    'attributeNames'=> [  // list of required attributes from public profile https://developers.facebook.com/docs/facebook-login/permissions
                        'email',
                        'name',
                        'first_name',
                        'last_name',
                        'gender',
                        'picture',
                        'link'
                    ],
                ]
            ],
        ],/*
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
    'as locale'=>[
        'class'=>'common\components\behaviors\LocaleBehavior'
    ],


    'params' => $params,
];
