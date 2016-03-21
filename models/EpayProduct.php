<?php

namespace app\models;

/**
 * This is the model class for table "tbl_epay_product".
 *
 * @property string $epp_id
 * @property string $epp_title
 * @property double $epp_denomination
 * @property string $epp_product_code
 * @property string $epp_amount_incent
 * @property string $epp_product_type
 * @property integer $epp_datetime
 */
class EpayProduct extends \yii\db\ActiveRecord
{
    const TYPE_ONLINE_PIN = 'PIN';
    const TYPE_ETOPUP = 'ETU';
    const TYPE_PAYMENT = 'PMT';
    const GST_INCLUDE = 1;
    const GST_EXCLUDE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_epay_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['epp_denomination'], 'number'],
            [['epp_product_type'], 'string'],
            [['epp_datetime','epp_gst'], 'integer'],
            [['epp_title', 'epp_product_code', 'epp_amount_incent'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'epp_id' => 'ID',
            'epp_title' => 'Title',
            'epp_denomination' => 'Denomination',
            'epp_product_code' => 'Product Code',
            'epp_amount_incent' => 'Amount Incent',
            'epp_product_type' => 'Product Type',
            'epp_datetime' => 'Datetime',
            'epp_gst' => 'Include GST',
        ];
    }

}
