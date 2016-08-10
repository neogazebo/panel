<?php

return [
    'class' => 'yii\db\Connection',
    'masterConfig' => [
        'username' => 'ebizu',
        'password' => 'ebizu4ebizu',
        'charset' => 'utf8',
    ],
    'masters' => [
        ['dsn' => 'mysql:host=dbx.ebizu.com;dbname=manisv3_db'],
    ],
];
