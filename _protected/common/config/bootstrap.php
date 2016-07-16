<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('root', dirname(dirname(dirname(__DIR__))) . '/');
Yii::setAlias('bin', dirname(dirname(dirname(__DIR__))) . '/_protected/vendor/bin');


Yii::setAlias('@backendUrl', "/backend");

// add class 'app-help-block' to all hints
Yii::$container->set('yii\bootstrap\ActiveField', [
    'hintOptions'   => [
        'class' => 'app-help-block help-block col-sm-3',
    ],
]);


