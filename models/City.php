<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_city".
 *
 * @property integer $cit_id
 * @property integer $cit_reference_id
 * @property integer $cit_region_id
 * @property string $cit_name
 * @property double $cit_latitude
 * @property double $cit_longitude
 * @property string $cit_description
 * @property string $cit_history
 * @property string $cit_practical_info
 * @property string $cit_getting_around
 * @property string $cit_tips
 * @property integer $cit_status
 * @property double $cit_avg_rating
 * @property integer $cit_time_zone
 * @property resource $cit_image
 * @property string $cit_image_name
 * @property string $cit_image_size
 * @property string $cit_image_type
 * @property integer $cit_count_views
 * @property integer $cit_count_review
 * @property integer $cit_count_members
 * @property integer $cit_count_corporate
 * @property integer $cit_count_events
 * @property integer $cit_weather_id
 * @property string $cit_sights
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cit_reference_id', 'cit_region_id', 'cit_name', 'cit_description', 'cit_practical_info', 'cit_getting_around', 'cit_tips', 'cit_time_zone', 'cit_image', 'cit_image_name', 'cit_image_size', 'cit_image_type', 'cit_count_views', 'cit_count_review', 'cit_count_members', 'cit_count_corporate', 'cit_count_events', 'cit_weather_id'], 'required'],
            [['cit_reference_id', 'cit_region_id', 'cit_status', 'cit_time_zone', 'cit_count_views', 'cit_count_review', 'cit_count_members', 'cit_count_corporate', 'cit_count_events', 'cit_weather_id'], 'integer'],
            [['cit_latitude', 'cit_longitude', 'cit_avg_rating'], 'number'],
            [['cit_description', 'cit_history', 'cit_practical_info', 'cit_getting_around', 'cit_tips', 'cit_image', 'cit_sights'], 'string'],
            [['cit_name'], 'string', 'max' => 100],
            [['cit_image_name'], 'string', 'max' => 50],
            [['cit_image_size'], 'string', 'max' => 10],
            [['cit_image_type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cit_id' => 'Cit ID',
            'cit_reference_id' => 'Cit Reference ID',
            'cit_region_id' => 'Cit Region ID',
            'cit_name' => 'Cit Name',
            'cit_latitude' => 'Cit Latitude',
            'cit_longitude' => 'Cit Longitude',
            'cit_description' => 'Cit Description',
            'cit_history' => 'Cit History',
            'cit_practical_info' => 'Cit Practical Info',
            'cit_getting_around' => 'Cit Getting Around',
            'cit_tips' => 'Cit Tips',
            'cit_status' => 'Cit Status',
            'cit_avg_rating' => 'Cit Avg Rating',
            'cit_time_zone' => 'Cit Time Zone',
            'cit_image' => 'Cit Image',
            'cit_image_name' => 'Cit Image Name',
            'cit_image_size' => 'Cit Image Size',
            'cit_image_type' => 'Cit Image Type',
            'cit_count_views' => 'Cit Count Views',
            'cit_count_review' => 'Cit Count Review',
            'cit_count_members' => 'Cit Count Members',
            'cit_count_corporate' => 'Cit Count Corporate',
            'cit_count_events' => 'Cit Count Events',
            'cit_weather_id' => 'Cit Weather ID',
            'cit_sights' => 'Cit Sights',
        ];
    }

    public static function find()
    {
        return new CityQuery(get_called_class());
    }
}
