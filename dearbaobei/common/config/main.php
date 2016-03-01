<?php
$params = array_merge(
    require(__DIR__ . '/params.php')
);
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'cookieValidationKey' => 'dearbaobei',
        ],
        'db'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=dearbaobei',
            'username' => 'root',
            'password' => 'dearbaobei@2016',
            'charset' => 'utf8',
            'tablePrefix' => 'baobei_',
        ],
    ],
    'aliases' => [
        '@asset_url' =>'/dear_assets',
    ],
    'timeZone'=>'Asia/Chongqing',
    'params' => $params
];
