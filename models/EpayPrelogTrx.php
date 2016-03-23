<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_epay_prelog_trx".
 *
 * @property integer $ept_id
 * @property integer $ept_datetime
 * @property integer $ept_red_id
 * @property string $ept_product_type
 * @property string $ept_ret_trans_ref
 * @property string $ept_transTraceId
 * @property string $ept_product_code
 * @property double $ept_amount
 * @property string $ept_msisdn
 * @property integer $ept_related_product_id
 */
class EpayPrelogTrx extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    public static function find()
    {
        return new EpayPrelogTrxQuery(get_called_class());
    }


    public static function tableName()
    {
        return 'tbl_epay_prelog_trx';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ept_datetime', 'ept_red_id', 'ept_related_product_id'], 'integer'],
            [['ept_product_type'], 'string'],
            [['ept_amount'], 'number'],
            [['ept_ret_trans_ref'], 'string', 'max' => 255],
            [['ept_transTraceId'], 'string', 'max' => 125],
            [['ept_product_code', 'ept_msisdn'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ept_id' => 'Ept ID',
            'ept_datetime' => 'Ept Datetime',
            'ept_red_id' => 'Ept Red ID',
            'ept_product_type' => 'Ept Product Type',
            'ept_ret_trans_ref' => 'Ept Ret Trans Ref',
            'ept_transTraceId' => 'Ept Trans Trace ID',
            'ept_product_code' => 'Ept Product Code',
            'ept_amount' => 'Ept Amount',
            'ept_msisdn' => 'Ept Msisdn',
            'ept_related_product_id' => 'Ept Related Product ID',
        ];
    }
}
