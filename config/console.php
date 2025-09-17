<?php

return [
    'id' => 'loan-api-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => '@app/migrations',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
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
    ],
    'params' => [],
];
