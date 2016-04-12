<?php
$awsUrl = 'https://d1307f5mo71yg9.cloudfront.net/';

return [
    'adminEmail' => 'noreply@ebizu.com',
    'supportEmail' => 'support@example.com',
    'awsUrl' => $awsUrl,
    'memberUrl' => $awsUrl . 'images/media/web/member/',
    'businessUrl' => $awsUrl . 'images/media/web/business/',
    'imageUrl' => $awsUrl . 'images/',
    'node' => 'http://node.ebizu.com',
    's3key' => 'AKIAJIL3GSRJLGYBLEJQ',
    's3secret' => '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',
    's3region' => 'ap-southeast-1',
    's3SESregion' => 'us-east-1',
    's3bucket' => 'ebizu-production',
    'EPAY_API_URL' => 'https://admin.ebizu.com/app/epay',
    'EPAY_TOKEN_API' => '3f2d64f31ab572ecf322d06a2b961755', //harcode merujuk ke=> red_key in tbl_redemption_partner => EBC dat,
    'EPAYSVC_NETWORKCHECK' => 'networkCheck',
    'EPAYSVC_ONLINEPIN' => 'onlinePIN',
    'EPAYSVC_ONLINEPIN_REVERSAL' => 'onlinePINReversal',
    'EPAYSVC_ETOPUP' => 'etopup',
    'EPAYSVC_ETOPUP_REVERSAL' => 'etopupReversal',
    'OPERATOR_ID' => 'IBS',
    // 'MERCHANT_ID' => '912401',
    // 'TERMINAL_ID' => '10000061',
    // 'MERCHANT_ID' => '202433',
    // 'TERMINAL_ID' => '80017419',
    // 'EPAY_OPERATOR_ID' => 'IBS',
    // 'EPAY_TERMINAL_ID' => '10000061',
    // 'EPAY_URL' => 'ws.oriongateway.com:33831',
    // 'EPAY_URL_PATH' => '/averni/services/oglws',
    // 'EPAY_URL' => 'ws.oriongateway.com:33831',
    // 'EPAY_URL_PATH' => '/averni/services/oglws',
    'flashmsg' => [
        'save' => [
            'success' => Yii::t('app', 'Data has been saved!'),
            'error' => Yii::t('app', 'Something wrong while saving data, please try again!'),
        ],
        'update' => [
            'success' => 'Data successfully updated!',
            'error' => Yii::t('app', 'Something wrong while updataing data, please try again!'),
        ],
        'delete' => [
            'success' => Yii::t('app', 'Data successfully deleted!'),
            'error' => Yii::t('app', 'Something wrong while deleting data, please try again!'),
        ],
    ],
];
