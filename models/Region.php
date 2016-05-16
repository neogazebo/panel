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

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->reg_name = mb_convert_encoding($this->reg_name, 'UTF-8');
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reg_country_id', 'reg_name'], 'required'],
            [['reg_country_id'], 'integer'],
            [['reg_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reg_id' => Yii::t('app', 'ID'),
            'reg_country_id' => Yii::t('app', 'Country'),
            'reg_name' => Yii::t('app', 'Region'),
        ];
    }
    
    public function getCountry() {
        return $this->hasOne(Country::className(), ['cny_id' => 'reg_country_id']);
    }
    
}
