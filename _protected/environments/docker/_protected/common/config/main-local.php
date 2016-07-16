<?php
return [
    'components' => [
        'db' => [
            'class'    => 'yii\db\Connection',
            'dsn'      => 'mysql:host=db;dbname=app',
            'username' => 'user',
            'password' => 'password',
            'charset'  => 'utf8',
        ],
    ],
];
