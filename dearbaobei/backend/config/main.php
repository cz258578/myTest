<?php
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '__Manage_identity', 'httpOnly' => true],
            'idParam' => '__Manage',
            'loginUrl'=>['/site/login']
        ],
        'db'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=dearbaobei',
            'username' => 'root',
            'password' => 'dearbaobei@2016',
            'charset' => 'utf8',
            'tablePrefix' => 'baobei_',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [

            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ]
    ]
];
