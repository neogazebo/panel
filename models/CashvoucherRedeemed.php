<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_cashvoucher_redeemed".
 *
 * @property integer $cvr_id
 * @property integer $cvr_acc_id
 * @property string $cvr_pvo_id
 * @property string $cvr_pvd_id
 * @property integer $cvr_pvd_update_datetime
 * @property string $cvr_pvd_code
 * @property string $cvr_pvd_sn
 * @property string $cvr_pvo_name
 * @property string $cvr_com_name
 * @property string $cvr_com_photo
 * @property string $cvr_pvo_image
 * @property integer $cvr_pvd_expired
 */
class CashvoucherRedeemed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cashvoucher_redeemed';
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
            [['cvr_acc_id', 'cvr_pvd_update_datetime', 'cvr_pvd_expired'], 'integer'],
            [['cvr_pvo_id', 'cvr_pvd_id', 'cvr_pvo_name', 'cvr_com_name'], 'string', 'max' => 250],
            [['cvr_pvd_code', 'cvr_pvd_sn'], 'string', 'max' => 150],
            [['cvr_com_photo', 'cvr_pvo_image'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cvr_id' => 'ID',
            'cvr_acc_id' => 'Account',
            'cvr_pvo_id' => 'Pos Voucher',
            'cvr_pvd_id' => 'Voucher Detail',
            'cvr_pvd_update_datetime' => 'Update Time',
            'cvr_pvd_code' => 'Code',
            'cvr_pvd_sn' => 'SN',
            'cvr_pvo_name' => 'Pos Voucher',
            'cvr_com_name' => 'Merchant',
            'cvr_com_photo' => 'Photo',
            'cvr_pvo_image' => 'Image',
            'cvr_pvd_expired' => 'Expired',
        ];
    }
}