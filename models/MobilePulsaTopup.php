<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_mobile_pulsa_topup".
 *
 * @property integer $mpt_id
 * @property integer $mpt_red_id
 * @property string $mpt_product_code
 * @property string $mpt_msisdn
 * @property string $mpt_ref_id
 * @property integer $mpt_status
 * @property string $mpt_message
 * @property integer $mpt_datetime
 */
class MobilePulsaTopup extends \yii\db\ActiveRecord
{
    public static function find()
    {
        return new MobilePulsaTopupQuery(get_called_class());
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mobile_pulsa_topup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpt_red_id','mpt_mem_id', 'mpt_status', 'mpt_datetime'], 'integer'],
            [['mpt_product_code', 'mpt_ref_id'], 'string', 'max' => 45],
            [['mpt_msisdn'], 'string', 'max' => 25],
            [['mpt_message'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mpt_id' => Yii::t('app', 'ID'),
            'mpt_red_id' => Yii::t('app', 'App Partner'),
            'mpt_mem_id' => Yii::t('app', 'Member'),
            'mpt_product_code' => Yii::t('app', 'Product Code'),
            'mpt_msisdn' => Yii::t('app', 'MSISDN'),
            'mpt_ref_id' => Yii::t('app', 'Ref ID'),
            'mpt_status' => Yii::t('app', 'Status'),
            'mpt_message' => Yii::t('app', 'Message'),
            'mpt_datetime' => Yii::t('app', 'Transaction Time'),
        ];
    }

    public function getPartner()
    {
        return $this->hasOne(RedemptionPartner::className(), ['red_id' => 'mpt_red_id']);
    }
    
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['mem_id' => 'mpt_mem_id']);
    }    

}