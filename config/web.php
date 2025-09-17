<?php

$config = [
    'id' => 'loan-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'loan-api-key-change-in-prod',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=' . ($_ENV['DB_HOST'] ?? 'postgres') . ';dbname=' . ($_ENV['DB_NAME'] ?? 'loans'),
            'username' => $_ENV['DB_USER'] ?? 'user',
            'password' => $_ENV['DB_PASSWORD'] ?? 'password',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST requests' => 'loan/create',
                'GET processor' => 'loan/process',
            ],
        ],
    ],
];

return $config;
