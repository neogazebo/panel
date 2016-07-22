<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=manisdb.ebizu.com;dbname=manisv3_db',
    'username' => 'manis',
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
                ['dsn' => 'mysql:host=manisdb2.ebizu.com;dbname=manisv3_db'],
                ['dsn' => 'mysql:host=manisdb3.ebizu.com;dbname=manisv3_db']
    ],
];
