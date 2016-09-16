<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_snapearn_rule".
 *
 * @property integer $ser_id
 * @property string $ser_country
 * @property integer $ser_point_cap
 * @property integer $ser_limit_approved_per_day_per_merchant
 * @property integer $ser_premium
 * @property integer $ser_created
 * @property integer $ser_updated
 * @property integer $ser_by
 */
class SnapEarnRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_snapearn_rule';
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
            [['ser_country'], 'required'],
            [['ser_point_cap', 'ser_limit_approved_per_day_per_merchant', 'ser_premium', 'ser_created', 'ser_updated', 'ser_by'], 'integer'],
            [['ser_country'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ser_id' => 'ID',
            'ser_country' => 'Country',
            'ser_point_cap' => 'Point Cap',
            'ser_limit_approved_per_day_per_merchant' => 'Limit Approved Per Day Per Merchant',
            'ser_premium' => 'Premium',
            'ser_created' => 'Created',
            'ser_updated' => 'Updated',
            'ser_by' => 'By',
        ];
    }

    public static function find()
    {
        return new SnapEarnRuleQuery(get_called_class());
    }
}
