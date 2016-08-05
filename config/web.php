<?php

$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config = [
    'id' => 'ebizu-bs',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'snapearn',
    'modules' => [
        'snapearn' => [
            'class' => 'app\modules\snapearn\Snapearn',
        ],
        'epay' => [
            'class' => 'app\modules\epay\Module'
        ],
        'voucher' => [
            'class' => 'app\modules\voucher\Voucher',
        ],
        'mobile' => [
            'class' => 'app\modules\mobile\Mobile',
        ],
        'merchant-signup' => [
            'class' => 'app\modules\merchant_signup\MerchantSignup',
        ],
        'account' => [
            'class' => 'app\modules\account\Account',
        ],
        'users' => [
            'class' => 'app\modules\users\Users',
        ],
        'rbac' => [
            'class' => 'app\modules\rbac\Rbac',
        ],
        'logwork' => [
            'class' => 'app\modules\logwork\Logwork',
        ],
        'voucher' => [
            'class' => 'app\modules\voucher\Voucher',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'W2DvT9IrYxWJM7BBJXtPywmjawYB6msU',
        ],
        'pushNotif' => [
            'class' => 'app\components\extentions\PushNotif'
        ],
        'encode' => [
            'class' => 'app\components\helpers\Encode'
        ],
        'general' => [
            'class' => 'app\components\helpers\GlobalHelper'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'AdminMail' => [
            'class' => 'app\components\extensions\AdminMail',
            'template' => '//mail/template/template',
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
        'db2' => require(__DIR__ . '/db2.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd MMM yyyy',
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
        ],
        'format' => [
            'class' => 'app\components\extensions\Formatter',
            'dateFormat' => 'dd-MM-yyyy',
            'decimalFormat' => '#,##0.00'
        ],
        'request' => [
            'enableCookieValidation' => false,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'access' => [
            'class' => 'app\components\filters\AccessFilters',
        ],
        'getRoutes' => [
            'class' => 'app\components\helpers\GetRoutes',
        ],
        // ftp setup
        'ftp' => [
            'class' => '\gftp\FtpComponent',
            'driverOptions' => [
                'class' => \gftp\FtpProtocol::valueOf('sftp')->driver,
                'user' => $params['EPAY_FTP_USER'],
                'pass' => $params['EPAY_FTP_PASSWORD'],
                'host' => $params['EPAY_FTP_HOST'],
                'port' => $params['EPAY_FTP_PORT'],
                'timeout' => 120
            ],
        ],
        // 'loggedin' => [
        //     'class' => 'app\components\extensions\Loggedin',
        // ],
        'array_helper' => [
            'class' => 'app\components\helpers\ArrayHelper',
        ]
    ],
    'params' => $params,
];

return $config;
