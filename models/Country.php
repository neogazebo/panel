<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_country".
 *
 * @property integer $cny_id
 * @property string $cny_name
 * @property string $cny_shortcode2
 * @property string $cny_shortcode3
 * @property integer $cny_prefix
 * @property integer $cny_mobile_prefix
 * @property string $cny_regex
 * @property integer $cny_continent_id
 * @property integer $cny_weather_id
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_country';
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->cny_name = mb_convert_encoding($this->cny_name, 'UTF-8');
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
            [['cny_prefix', 'cny_mobile_prefix', 'cny_regex', 'cny_continent_id', 'cny_weather_id'], 'required'],
            [['cny_prefix', 'cny_mobile_prefix', 'cny_continent_id', 'cny_weather_id'], 'integer'],
            [['cny_name'], 'string', 'max' => 100],
            [['cny_shortcode2'], 'string', 'max' => 2],
            [['cny_shortcode3'], 'string', 'max' => 3],
            [['cny_regex'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cny_id' => 'ID',
            'cny_name' => 'Country',
            'cny_shortcode2' => 'Shortcode2',
            'cny_shortcode3' => 'Shortcode3',
            'cny_prefix' => 'Prefix',
            'cny_mobile_prefix' => 'Mobile Prefix',
            'cny_regex' => 'Regex',
            'cny_continent_id' => 'Continent ID',
            'cny_weather_id' => 'Weather ID',
        ];
    }
}
