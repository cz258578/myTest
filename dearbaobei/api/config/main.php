<?php
return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
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
            'suffix' => '',
            'rules' => [

            /*['class' => 'yii\rest\UrlRule', 'controller' => 'grade'],
            ['class' => 'yii\rest\UrlRule', 'controller' => 'admin-user'],
            [
             '<controller:(post|comment)>/<id:\d+>/<action:(create|update|delete)>' => '<controller>/<action>',
             '<controller:(post|comment)>/<id:\d+>' => '<controller>/read',
             '<controller:(post|comment)>s' => '<controller>/list',
            ]*/
            ],
        ],      
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ]
];
