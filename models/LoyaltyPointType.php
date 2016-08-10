<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_loyalty_point_type".
 *
 * @property integer $lpe_id
 * @property string $lpe_name
 * @property integer $lpe_recursive
 * @property integer $lpe_daily_cap
 * @property integer $lpe_hourly_cap
 * @property integer $lpe_point
 * @property integer $lpe_valid
 */
class LoyaltyPointType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_loyalty_point_type';
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
            [['lpe_recursive', 'lpe_daily_cap', 'lpe_hourly_cap', 'lpe_point', 'lpe_valid'], 'integer'],
            [['lpe_name'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lpe_id' => 'Lpe ID',
            'lpe_name' => 'Lpe Name',
            'lpe_recursive' => 'Lpe Recursive',
            'lpe_daily_cap' => 'Lpe Daily Cap',
            'lpe_hourly_cap' => 'Lpe Hourly Cap',
            'lpe_point' => 'Lpe Point',
            'lpe_valid' => 'Lpe Valid',
        ];
    }

    /**
     * @inheritdoc
     * @return LoyaltyPointTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoyaltyPointTypeQuery(get_called_class());
    }
}
