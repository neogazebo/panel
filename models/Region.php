<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_region".
 *
 * @property integer $reg_id
 * @property integer $reg_country_id
 * @property string $reg_name
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reg_country_id', 'reg_name'], 'required'],
            [['reg_country_id'], 'integer'],
            [['reg_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reg_id' => 'Reg ID',
            'reg_country_id' => 'Reg Country ID',
            'reg_name' => 'Reg Name',
        ];
    }

    /**
     * @inheritdoc
     * @return RegionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegionQuery(get_called_class());
    }
}
