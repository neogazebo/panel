<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=db.ebizu.com;dbname=ebdb',
    'username' => 'admin',
    'password' => 'EB!zu-43bizu*@',
    'charset' => 'utf8',
    'slaveConfig' => [
		'username' => 'admin',
		'password' => 'EB!zu-43bizu*@',
		'attributes' => [
               PDO::ATTR_TIMEOUT => 10,
           ],
    'charset' => 'utf8',
        ],
    'slaves' => [
                ['dsn' => 'mysql:host=db2.ebizu.com;dbname=ebdb'],
                ['dsn' => 'mysql:host=db3.ebizu.com;dbname=ebdb'],
                ['dsn' => 'mysql:host=db4.ebizu.com;dbname=ebdb'],
    ],
];