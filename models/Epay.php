<?php

namespace app\models;

/**
 * This is the model class for table "tbl_epay".
 *
 * @property string $epa_id
 * @property integer $epa_admin_id
 * @property string $epa_admin_name
 * @property integer $epa_datetime
 * @property integer $epa_qty
 * @property integer $epa_success_qty
 * @property integer $epa_failed_qty
 */
use yii\db\ActiveRecord;

class Epay extends \yii\db\ActiveRecord
{    
    public $epa_epp_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_epay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['epa_qty'], 'required', 'on' => 'default'],
            [['epa_admin_id', 'epa_datetime', 'epa_qty', 'epa_success_qty', 'epa_failed_qty', 'epa_vou_id'], 'integer'],
            [['epa_admin_name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'epa_id' => 'ID',
            'epa_admin_id' => 'Admin',
            'epa_admin_name' => 'Admin Name',
            'epa_datetime' => 'Created On',
            'epa_qty' => 'Quantity',
            'epa_success_qty' => 'Success Qty',
            'epa_failed_qty' => 'Failed Qty',
            'epa_vou_id' => 'Voucher',
            'epa_epp_id' => 'Product',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['epa_datetime'],
                ],
            ],
        ];
    }

    public function getReward()
    {
        return $this->hasOne(Voucher::className(), ['vou_id' => 'epa_vou_id']);
    }

    public function getProduct()
    {
        return EpayProduct::find()->where('epp_product_type=:type AND epp_gst=:gst', ['type' => EpayProduct::TYPE_ONLINE_PIN,'gst'=> EpayProduct::GST_INCLUDE])->all();
    }

    public function getVoucher()
    {
        return Voucher::find()->all();
    }
    
    public function productInfo()
    {
        return EpayProduct::findOne($this->epa_epp_id);
    }    

    public static function find()
    {
        return new EpayQuery(get_called_class());
    }

}
