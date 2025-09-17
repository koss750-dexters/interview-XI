<?php

return [
    'id' => 'loan-api-test',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=postgres;dbname=loans_test',
            'username' => 'user',
            'password' => 'password',
        ],
        'request' => [
            'cookieValidationKey' => 'test-key',
            'scriptFile' => __DIR__ . '/../web/index.php',
            'scriptUrl' => '/index.php',
        ],
    ],
];
