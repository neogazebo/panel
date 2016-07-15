<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_redemption_reference".
 *
 * @property integer $rdr_id
 * @property integer $rdr_acc_id
 * @property integer $rdr_vou_id
 * @property integer $rdr_vou_type
 * @property string $rdr_msisdn
 * @property string $rdr_reference_code
 * @property integer $rdr_status
 * @property integer $rdr_datetime
 * @property string $rdr_vod_sn
 * @property string $rdr_vod_code
 * @property string $rdr_vod_expired
 * @property string $rdr_vor_trx_id
 * @property string $rdr_com_photo
 * @property integer $rdr_vou_value
 * @property string $rdr_name
 * @property string $rdr_vod_image
 */
class RedemptionReference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_redemption_reference';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rdr_acc_id', 'rdr_vou_id', 'rdr_vou_type', 'rdr_status', 'rdr_datetime', 'rdr_vou_value'], 'integer'],
            [['rdr_msisdn', 'rdr_vor_trx_id'], 'string', 'max' => 50],
            [['rdr_reference_code'], 'string', 'max' => 250],
            [['rdr_vod_sn', 'rdr_vod_code', 'rdr_com_photo', 'rdr_vod_image'], 'string', 'max' => 100],
            [['rdr_vod_expired'], 'string', 'max' => 10],
            [['rdr_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rdr_id' => 'ID',
            'rdr_acc_id' => 'Account',
            'rdr_vou_id' => 'Voucher',
            'rdr_vou_type' => 'Type',
            'rdr_msisdn' => 'MSISDN',
            'rdr_reference_code' => 'Reference Code',
            'rdr_status' => 'Status',
            'rdr_datetime' => 'Created',
            'rdr_vod_sn' => 'SN',
            'rdr_vod_code' => 'Code',
            'rdr_vod_expired' => 'Expired',
            'rdr_vor_trx_id' => 'Transaction',
            'rdr_com_photo' => 'Photo',
            'rdr_vou_value' => 'Value',
            'rdr_name' => 'Name',
            'rdr_vod_image' => 'Image',
        ];
    }
}