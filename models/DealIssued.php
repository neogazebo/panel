<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_deal_issued".
 *
 * @property integer $des_id
 * @property integer $des_del_id
 * @property integer $des_receiver_id
 * @property integer $des_datetime
 * @property string $des_sent
 * @property integer $des_redeem_datetime
 * @property integer $des_redeem_merchant_id
 * @property string $des_code
 * @property string $des_sn
 * @property string $des_expired_flag
 * @property string $des_sms_flag
 * @property string $des_email_flag
 * @property string $des_message_flag
 * @property integer $des_read
 * @property integer $des_der_id
 */
class DealIssued extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_deal_issued';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['des_del_id', 'des_receiver_id', 'des_datetime', 'des_redeem_merchant_id', 'des_code', 'des_sn'], 'required'],
            [['des_del_id', 'des_receiver_id', 'des_datetime', 'des_redeem_datetime', 'des_redeem_merchant_id', 'des_read', 'des_der_id'], 'integer'],
            [['des_sent', 'des_expired_flag', 'des_sms_flag', 'des_email_flag', 'des_message_flag'], 'string'],
            [['des_code'], 'string', 'max' => 25],
            [['des_sn'], 'string', 'max' => 100],
            [['des_sn'], 'unique'],
        ];
    }

    public function getOffer()
    {
        return $this->hasOne(Deal::className(), ['del_id' => 'des_del_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'des_id' => 'ID',
            'des_del_id' => 'Promotion',
            'des_receiver_id' => 'Receiver',
            'des_datetime' => 'Datetime',
            'des_sent' => 'Sent',
            'des_redeem_datetime' => 'Redeem Time',
            'des_redeem_merchant_id' => 'Redeem Merchant',
            'des_code' => 'Code',
            'des_sn' => 'SN',
            'des_expired_flag' => 'Expired Flag',
            'des_sms_flag' => 'Sms Flag',
            'des_email_flag' => 'Email Flag',
            'des_message_flag' => 'Message Flag',
            'des_read' => 'Read',
            'des_der_id' => 'Der ID',
        ];
    }
}