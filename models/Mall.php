<?php

namespace app\models;

use Yii;
use app\models\EbizuActiveRecord;

/**
 * This is the model class for table "tbl_mall".
 *
 * @property integer $mal_id
 * @property integer $mal_datetime
 * @property string $mal_name
 * @property string $mal_code
 * @property string $mal_city
 * @property integer $mal_city_id
 * @property integer $mal_region_id
 * @property integer $mal_country_id
 * @property string $mal_description
 * @property double $mal_lat
 * @property double $mal_lng
 * @property integer $mal_status
 * @property string $mal_address
 * @property string $mal_postcode
 * @property string $mal_email
 * @property string $mal_website
 * @property string $mal_photo
 * @property string $mal_key
 * @property string $mal_building_id
 * @property string $mal_web_img_header
 * @property string $mal_share_business
 * @property string $mal_share_event
 * @property string $mal_share_offer
 * @property string $mal_share_voucher
 * @property string $mal_share_reward
 * @property string $mal_open_monday
 * @property string $mal_close_monday
 * @property integer $mal_status_monday
 * @property string $mal_open_tuesday
 * @property string $mal_close_tuesday
 * @property integer $mal_status_tuesday
 * @property string $mal_open_wednesday
 * @property string $mal_close_wednesday
 * @property integer $mal_status_wednesday
 * @property string $mal_open_thursday
 * @property string $mal_close_thursday
 * @property integer $mal_status_thursday
 * @property string $mal_open_friday
 * @property string $mal_close_friday
 * @property integer $mal_status_friday
 * @property string $mal_open_saturday
 * @property string $mal_close_saturday
 * @property integer $mal_status_saturday
 * @property string $mal_open_sunday
 * @property string $mal_close_sunday
 * @property integer $mal_status_sunday
 * @property string $mal_gcm_key
 * @property string $mal_apns_key
 * @property integer $mal_loyalty
 * @property integer $mal_ebizu_snap_earn
 */
class Mall extends EbizuActiveRecord
{

    const NU_SENTRAL_ID = 13;
    const BSC_ID = 17;
    const MAG_ID = 71;
    const IOI_CITY = 222;
    const KOKAS_ID = 60;
    const GANCIT_ID = 36;
    public $total = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mal_datetime', 'mal_city_id', 'mal_region_id', 'mal_country_id', 'mal_status', 'mal_status_monday', 'mal_status_tuesday', 'mal_status_wednesday', 'mal_status_thursday', 'mal_status_friday', 'mal_status_saturday', 'mal_status_sunday', 'mal_loyalty', 'mal_ebizu_snap_earn'], 'integer'],
            [['mal_description'], 'string'],
            [['mal_lat', 'mal_lng'], 'number'],
            [['mal_open_monday', 'mal_close_monday', 'mal_open_tuesday', 'mal_close_tuesday', 'mal_open_wednesday', 'mal_close_wednesday', 'mal_open_thursday', 'mal_close_thursday', 'mal_open_friday', 'mal_close_friday', 'mal_open_saturday', 'mal_close_saturday', 'mal_open_sunday', 'mal_close_sunday'], 'safe'],
            [['mal_name', 'mal_city', 'mal_email', 'mal_website'], 'string', 'max' => 200],
            [['mal_code'], 'string', 'max' => 5],
            [['mal_address'], 'string', 'max' => 500],
            [['mal_postcode'], 'string', 'max' => 20],
            [['mal_photo', 'mal_building_id'], 'string', 'max' => 100],
            [['mal_key', 'mal_web_img_header', 'mal_share_business', 'mal_share_event', 'mal_share_offer', 'mal_share_voucher', 'mal_share_reward', 'mal_gcm_key', 'mal_apns_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mal_id' => 'ID',
            'mal_datetime' => 'Datetime',
            'mal_name' => 'Name',
            'mal_code' => 'Code',
            'mal_city' => 'City',
            'mal_city_id' => 'City ID',
            'mal_region_id' => 'Region ID',
            'mal_country_id' => 'Country ID',
            'mal_description' => 'Description',
            'mal_lat' => 'Lat',
            'mal_lng' => 'Lng',
            'mal_status' => 'Status',
            'mal_address' => 'Address',
            'mal_postcode' => 'Postcode',
            'mal_email' => 'Email',
            'mal_website' => 'Website',
            'mal_photo' => 'Photo',
            'mal_key' => 'Key',
            'mal_building_id' => 'Building ID',
            'mal_web_img_header' => 'Web Img Header',
            'mal_share_business' => 'Share Business',
            'mal_share_event' => 'Share Event',
            'mal_share_offer' => 'Share Offer',
            'mal_share_voucher' => 'Share Voucher',
            'mal_share_reward' => 'Share Reward',
            'mal_open_monday' => 'Open Monday',
            'mal_close_monday' => 'Close Monday',
            'mal_status_monday' => 'Status Monday',
            'mal_open_tuesday' => 'Open Tuesday',
            'mal_close_tuesday' => 'Close Tuesday',
            'mal_status_tuesday' => 'Status Tuesday',
            'mal_open_wednesday' => 'Open Wednesday',
            'mal_close_wednesday' => 'Close Wednesday',
            'mal_status_wednesday' => 'Status Wednesday',
            'mal_open_thursday' => 'Open Thursday',
            'mal_close_thursday' => 'Close Thursday',
            'mal_status_thursday' => 'Status Thursday',
            'mal_open_friday' => 'Open Friday',
            'mal_close_friday' => 'Close Friday',
            'mal_status_friday' => 'Status Friday',
            'mal_open_saturday' => 'Open Saturday',
            'mal_close_saturday' => 'Close Saturday',
            'mal_status_saturday' => 'Status Saturday',
            'mal_open_sunday' => 'Open Sunday',
            'mal_close_sunday' => 'Close Sunday',
            'mal_status_sunday' => 'Status Sunday',
            'mal_gcm_key' => 'Gcm Key',
            'mal_apns_key' => 'Apns Key',
            'mal_loyalty' => 'Loyalty',
            'mal_ebizu_snap_earn' => 'Ebizu Snap Earn',
        ];
    }

    // public function behaviors() {
        // return [
        //     'img-web-header' => [
        //         'class' => 'app\components\behaviors\S3Behavior',
        //         'field' => 'mal_web_img_header',
        //         'path' => 'images/media/web/business',
        //         'size' => [
        //             'width' => '1024',
        //             'height' => '600'
        //         ]
        //     ],
        //     'Location' => [
        //         'class' => 'app\components\behaviors\ExplodeLocation',
        //         'location' => 'mal_city',
        //         'attributes' => [
        //             'city' => 'mal_city_id',
        //             'region' => 'mal_region_id',
        //             'country' => 'mal_country_id'
        //         ]
        //     ],
        // ];
    // }

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
        return \app\components\helpers\Html::listData($model, 'mac_id', 'mac_name');
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

    /**
     * @inheritdoc
     * @return MallQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MallQuery(get_called_class());
    }
}
