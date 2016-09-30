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
    'EPAY_FTP_USER' => 'ebizu',
    'EPAY_FTP_PASSWORD' => 'Mbr6khXJ79kAY',
    'EPAY_FTP_HOST' => 'sftp.e-pay.com.my',
    'EPAY_FTP_PORT' => 22,
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
    'frontendUrl' => 'https://biz.ebizu.com/',
    'AWS_REGION' => 'ap-northeast-1',
    'AWS_ACCESS_KEY_ID' => "AKIAJIL3GSRJLGYBLEJQ",
    'AWS_SECRET_ACCESS_KEY' => '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',
    'RETAILER_SQS_URL' => 'https://sqs.ap-northeast-1.amazonaws.com/586061393339/Retailer-Merchant'
];
