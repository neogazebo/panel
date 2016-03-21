<?php

namespace app\models;

/**
 * This is the model class for table "tbl_voucher_bought".
 *
 * @property string $vob_id
 * @property integer $vob_com_id
 * @property integer $vob_datetime
 * @property integer $vob_datetime_bought
 * @property integer $vob_qty
 * @property double $vob_price
 * @property integer $vob_vou_id
 */
use yii\db\ActiveRecord;

class VoucherBought extends \yii\db\ActiveRecord
{
	public $qty; 

    public static function find()
    {
        return new VoucherBoughtQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_voucher_bought';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['vob_datetime'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vob_vou_id', 'vob_qty', 'vob_price'], 'required'],
            [['vob_com_id', 'vob_qty', 'vob_vou_id'], 'integer'],
            [['vob_price'], 'number'],
            [['vob_datetime', 'vob_datetime_bought'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vob_id' => 'ID',
            'vob_com_id' => 'Business',
            'vob_datetime' => 'Created On',
            'vob_datetime_bought' => 'Datetime Bought',
            'vob_qty' => 'Qty',
            'vob_price' => 'Price',
            'vob_vou_id' => 'Voucher',
            'qty' => 'Qty',
        ];
    }

    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['vou_id' => 'vob_vou_id']);
    }

    public function getDetail()
    {
        return $this->hasOne(VoucherBoughtDetail::className(), ['vod_vob_id' => 'vob_id']);
    }

    public function getImage()
    {
        if (!empty($this->voucher->vou_image))
            return Yii::$app->params['businessUrl'] . $this->voucher->vou_image;
        return Yii::$app->homeUrl . 'img/90.jpg';
    }

}
