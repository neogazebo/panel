<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Mall extends EbizuActiveRecord
{    
    const NU_SENTRAL_ID = 13;
    const BSC_ID = 17;
    const MAG_ID = 71;
    const IOI_CITY = 222;
    const KOKAS_ID = 60;
    const GANCIT_ID = 36;
    public $total = 0;

    public static function find()
    {
        return new MallQuery(get_called_class());
    }

    public static function tableName()
    {
        return 'tbl_mall';
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            $this->mal_name = mb_convert_encoding($this->mal_name, 'UTF-8');
            $this->mal_description = mb_convert_encoding($this->mal_description, 'UTF-8');
            $this->mal_address = mb_convert_encoding($this->mal_address, 'UTF-8');
            return true;
        }
        return false;
    }

    public function rules()
    {
        return [
            [['mal_name', 'mal_postcode', 'mal_address', 'mal_status', 'mal_city', 'mal_city_id', 'mal_region_id', 'mal_country_id', 'mal_description', 'mal_lat', 'mal_lng', 'mal_web_img_header', 'mal_loyalty','mal_ebizu_snap_earn'], 'safe'],
            [['mal_name', 'mal_city'], 'required'],
            ['mal_email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mal_id' => 'ID',
            'mal_datetime' => 'Created On',
            'mal_name' => 'Mall',
            'mal_city' => 'City',
            'mal_city_id' => 'City',
            'mal_region_id' => 'Region',
            'mal_country_id' => 'Country',
            'mal_description' => 'Description',
            'mal_lat' => 'Latitude',
            'mal_lng' => 'Longitude',
            'mal_status' => 'Status',
            'mal_email' => 'Email',
            'mal_website' => 'Website',
            'mal_address' => 'Address',
            'mal_postcode' => 'Postcode',
            'mal_photo' => 'Photo',
            'mal_key' => 'Key',
            'mal_building_id' => 'Building ID',
            'mal_web_img_header' => 'Image Header',
            'mal_ebizu_snap_earn' => 'Mall Ebizu Snap Earn'
        ];
    }
    
    public function behaviors() {
        return [
            'img-web-header' => [
                'class' => 'common\components\behaviors\S3Behavior',
                'field' => 'mal_web_img_header',
                'path' => 'images/media/web/business',
                'size' => [
                    'width' => '1024',
                    'height' => '600'
                ]
            ],
            'Location' => [
                'class' => 'common\components\behaviors\ExplodeLocation',
                'location' => 'mal_city',
                'attributes' => [
                    'city' => 'mal_city_id',
                    'region' => 'mal_region_id',
                    'country' => 'mal_country_id'
                ]
            ],
        ];
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['cit_id' => 'mal_city_id']);
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['reg_id' => 'mal_region_id']);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['cny_id' => 'mal_country_id']);
    }

    public function getImage()
    {
        if (!empty($this->mal_photo))
            return Yii::$app->params['businessUrl'] . $this->mal_photo;
        return Yii::$app->homeUrl . 'img/90.jpg';
    }

    public static function listData()
    {
        $model = Mall::find()->orderBy('mal_name')->all();
        return \yii\helpers\ArrayHelper::map($model, 'mal_id', 'mal_name');
    }

    public static function listDataById($id)
    {
        $model = Mall::find()->where('mal_id='.$id)->all();

        return \yii\helpers\ArrayHelper::map($model, 'mal_id', 'mal_name');
    }

    public static function listDataByMallId($id)
    {
        $model = MallCategory::find()->where('mac_mal_id='.$id)->all();
        // return \yii\helpers\ArrayHelper::map($model, 'mac_id', 'mac_name');
        return \common\components\helpers\Html::listData($model, 'mac_id', 'mac_name');
    }

    public static function topCategories($date)
    {
        $query = self::find()
                ->select('mac_name as label, COUNT(*) as value')
                ->from('tbl_mall_category')
                ->innerJoin('tbl_company com', 'com.com_mac_id = mac_id')
                ->where('mac_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->groupBy('mac_id')
                ->orderBy('count(*) DESC');
                //->limit(10);
        $command = $query->createCommand();
        $rows = $command->queryAll();

        $data = [['category', 'category']];

        foreach ($rows as $row) {
            $data[] = [0 => $row['label'], 1 => (int) $row['value']];
        }

        return $data;
    }
}
