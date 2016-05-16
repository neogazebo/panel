<?php

namespace common\models;

use Yii;

class City extends \yii\db\ActiveRecord
{
    public $cny_id = null;

    public static function find()
    {
        return new CityQuery(get_called_class());
    }

    public static function tableName()
    {
        return 'tbl_city';
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            if (!$this->isNewRecord) {
                $this->cit_status = 0;
            } else {
                $this->cit_status = 1;
            }
            $this->cit_name = mb_convert_encoding($this->cit_name, 'UTF-8');
            $this->cit_description = mb_convert_encoding($this->cit_description, 'UTF-8');
            return true;
        }
        return false;
    }

    public function rules() {
        return [
            [['cit_reference_id', 'cit_region_id', 'cit_name', 'cit_description', 'cit_practical_info', 'cit_getting_around', 'cit_tips', 'cit_time_zone', 'cit_image', 'cit_image_name', 'cit_image_size', 'cit_image_type', 'cit_count_views', 'cit_count_review', 'cit_count_members', 'cit_count_corporate', 'cit_count_events', 'cit_weather_id'], 'safe'],
            [['cit_reference_id', 'cit_region_id', 'cit_status', 'cit_time_zone', 'cit_count_views', 'cit_count_review', 'cit_count_members', 'cit_count_corporate', 'cit_count_events', 'cit_weather_id'], 'integer'],
            [['cit_latitude', 'cit_longitude', 'cit_avg_rating'], 'number'],
            [['cit_description', 'cit_history', 'cit_practical_info', 'cit_getting_around', 'cit_tips', 'cit_image', 'cit_sights'], 'string'],
            [['cit_name'], 'string', 'max' => 100],
            [['cit_image_name'], 'string', 'max' => 50],
            [['cit_image_size'], 'string', 'max' => 10],
            [['cit_image_type'], 'string', 'max' => 30],
            [['cit_region_id', 'cny_id', 'cit_name'], 'required', 'on' => 'newcity']
        ];
    }

    public function attributeLabels() {
        return [
            'cit_id' => 'ID',
            'cit_reference_id' => 'unique reference id from tbl reference',
            'cit_region_id' => 'Region',
            'cit_name' => 'Name',
            'cit_latitude' => 'Latitude',
            'cit_longitude' => 'Longitude',
            'cit_description' => 'Description',
            'cit_history' => 'History of the city',
            'cit_practical_info' => 'Practical Info',
            'cit_getting_around' => 'Getting Around',
            'cit_tips' => 'Tips',
            'cit_status' => 'Status',
            'cit_avg_rating' => 'Avg Rating',
            'cit_time_zone' => 'Time Zone',
            'cit_image' => 'Image',
            'cit_image_name' => 'Image Name',
            'cit_image_size' => 'Image Size',
            'cit_image_type' => 'Image Type',
            'cit_count_views' => 'Count Views',
            'cit_count_review' => 'Count Review',
            'cit_count_members' => 'Count Members',
            'cit_count_corporate' => 'Count Corporate',
            'cit_count_events' => 'Count Events',
            'cit_weather_id' => 'Weather ID',
            'cit_sights' => 'Sights',
            'cny_id' => 'Country',
        ];
    }

    public function getCitRegion() {
        return $this->hasOne(Region::className(), ['reg_id' => 'cit_region_id']);
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['reg_id' => 'cit_region_id']);
    }

    public function getCountry()
    {
        $country = (new yii\db\Query())
            ->select('cny_id, cny_name')
            ->from('tbl_city')
            ->leftJoin('tbl_region b', 'b.reg_id = cit_region_id')
            ->leftJoin('tbl_country c', 'c.cny_id = b.reg_country_id')
            ->where('cit_id = :id', [':id' => $this->cit_id])
            ->one();
        return $country;
    }

    /**
     * Get cities with name like param
     * 
     * @param string $searchText
     * @return array of City
     */
    public static function getCitiesLikeName($searchText) {
        $cities = self::find()
            ->where(['like', 'cit_name', $searchText])
            ->limit(10)
            ->all();
        return $cities;
    }

    public static function getCityByName($name) {
        $city = self::find()
            ->where(['cit_name' => $name])
            ->one();
        return $city;
    }       

    public static function addressIds(array $address) {
        $query = "
			SELECT a.cit_id, a.cit_name, b.reg_id, b.reg_name, c.cny_id, c.cny_name
			FROM tbl_city a, tbl_region b, tbl_country c
			WHERE a.cit_region_id = b.reg_id
				AND b.reg_country_id = c.cny_id
				AND a.cit_name = '" . trim($address[0]) . "'
                                AND b.reg_name = '" . trim($address[1]) . "'
                                AND c.cny_name = '" . trim($address[2]) . "'
			ORDER BY a.cit_name
			LIMIT 10";
        $connection = Yii::$app->db;
        $query = $connection->createCommand($query)->queryAll();
        if (count($query) > 0)
            return [
                'cit_id' => $query[0]['cit_id'],
                'reg_id' => $query[0]['reg_id'],
                'cny_id' => $query[0]['cny_id'],
            ];
    }
    
    public static function findLocation($q){
        $query = "
			SELECT a.cit_id, a.cit_name, b.reg_id, b.reg_name, c.cny_id, c.cny_name
			FROM tbl_city a, tbl_region b, tbl_country c
			WHERE a.cit_region_id = b.reg_id
				AND b.reg_country_id = c.cny_id
				AND a.cit_name LIKE '%" . $q . "%'
			ORDER BY a.cit_name
			LIMIT 10";
        $connection = Yii::$app->db;
        $query = $connection->createCommand($query)->queryAll();
        $return = [];
        foreach ($query as $row) {
            $return[]['value'] =  $row['cit_name'] . ', ' . $row['reg_name'] . ', ' . $row['cny_name'];
        }
        return $return;
    }

}
