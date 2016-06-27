<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_mobile_pulsa_product".
 *
 * @property integer $mpp_id
 * @property string $mpp_product_code
 * @property string $mpp_operator
 * @property string $mpp_nominal
 * @property string $mpp_price
 * @property string $mpp_active_periode
 * @property integer $mpp_created_at
 * @property integer $mpp_last_updated
 */
class MobilePulsaProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mobile_pulsa_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_created_at', 'mpp_last_updated'], 'integer'],
            [['mpp_product_code'], 'string', 'max' => 45],
            [['mpp_operator', 'mpp_nominal', 'mpp_price', 'mpp_active_periode'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mpp_id' => Yii::t('app', 'Mpp ID'),
            'mpp_product_code' => Yii::t('app', 'Product Code'),
            'mpp_operator' => Yii::t('app', 'Operator'),
            'mpp_nominal' => Yii::t('app', 'Nominal'),
            'mpp_price' => Yii::t('app', 'Price'),
            'mpp_active_periode' => Yii::t('app', 'Active Periode'),
            'mpp_created_at' => Yii::t('app', 'Created At'),
            'mpp_last_updated' => Yii::t('app', 'Last Updated'),
        ];
    }
    
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['mpp_created_at','mpp_last_updated'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['mpp_last_updated'],
                ],
            ],
        ];
    }    
}
