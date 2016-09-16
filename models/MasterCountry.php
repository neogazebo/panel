<?php

namespace app\models;

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
class MasterCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_country';
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
            [['cny_regex'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cny_id' => 'Cny ID',
            'cny_name' => 'Cny Name',
            'cny_shortcode2' => 'Cny Shortcode2',
            'cny_shortcode3' => 'Cny Shortcode3',
            'cny_prefix' => 'Cny Prefix',
            'cny_mobile_prefix' => 'Cny Mobile Prefix',
            'cny_regex' => 'Cny Regex',
            'cny_continent_id' => 'Cny Continent ID',
            'cny_weather_id' => 'Cny Weather ID',
        ];
    }
}
