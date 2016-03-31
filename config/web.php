<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'ebizu-bs',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'epay' => [
            'class' => 'app\modules\epay\Module'
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'W2DvT9IrYxWJM7BBJXtPywmjawYB6msU',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/AdminLTE',
                'baseUrl' => '@web/themes/AdminLTE',
                'pathMap' => [
                    '@app/views' => '@app/themes/AdminLTE',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'formater' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'M . d Y',
        ],
        'request' => [
            'enableCookieValidation' => false,
        ],
        'ftp' => [
            'class' => '\gftp\FtpComponent',
            'driverOptions' => [
                'class' => \gftp\FtpProtocol::valueOf('sftp')->driver,
                'user' => 'ebizu',
                'pass' => 'Mbr6khXJ79kAY',
                'host' => 'sftp.e-pay.com.my',
                'port' => 22,
                'timeout' => 120
            ],
        ],
        'loggedin' => [
            'class' => 'app\components\extensions\Loggedin',
        ],
    ],
    'params' => $params,
];
return $config;
