<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_account".
 *
 * @property integer $acc_id
 * @property integer $acc_facebook_id
 * @property string $acc_facebook_email
 * @property string $acc_facebook_graph
 * @property string $acc_google_id
 * @property string $acc_google_email
 * @property string $acc_google_token
 * @property string $acc_screen_name
 * @property string $acc_cty_id
 * @property string $acc_photo
 * @property integer $acc_created_datetime
 * @property integer $acc_updated_datetime
 * @property integer $acc_status
 * @property integer $acc_tmz_id
 * @property integer $acc_birthdate
 * @property string $acc_address
 * @property integer $acc_gender
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_account';
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
            [['acc_facebook_id', 'acc_facebook_email', 'acc_facebook_graph', 'acc_screen_name', 'acc_photo', 'acc_created_datetime', 'acc_tmz_id'], 'required'],
            [['acc_facebook_id', 'acc_created_datetime', 'acc_updated_datetime', 'acc_status', 'acc_tmz_id', 'acc_birthdate', 'acc_gender'], 'integer'],
            [['acc_facebook_graph'], 'string'],
            [['acc_facebook_email', 'acc_screen_name'], 'string', 'max' => 50],
            [['acc_google_id', 'acc_google_email', 'acc_google_token'], 'string', 'max' => 1],
            [['acc_cty_id'], 'string', 'max' => 2],
            [['acc_photo'], 'string', 'max' => 35],
            [['acc_address'], 'string', 'max' => 200],
            [['acc_facebook_id'], 'unique'],
        ];
    }

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['cty_short_code' => 'acc_cty_id']);
    }

    public function getTimezone()
    {
        return $this->hasOne(TimeZone::className(), ['acc_tmz_id' => 'tmz_id']);
    }

    public function lastPointMember()
    {
        $model = LoyaltyPointHistory::find()->getTotalPointMember($this->acc_id);
        $point = (!empty($model->lph_total_point)) ? $model->lph_total_point : '0';
        return $point;
    }

    public function lastLocation()
    {
        $model = AccountDevice::find()->getLastLocatione($this->acc_id);
        $location = $model->all();
        return $location;
    }

    public function lastLogin()
    {
        $model = AccountDevice::find()->getLastLocatione($this->acc_id);
        $location = $model->one();
        return $location;
    }

    public function lastSnapUpload()
    {
        $model = SnapEarn::find()->getLastUpload($this->acc_id);
        $upload = (!empty($model->all())) ? $model->all() : '0';
        return $upload;
    }

    public function activeDevice()
    {
        $model = AccountDevice::find()->getActiveDevice($this->acc_id);
        return $model;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'acc_id' => 'ID',
            'acc_facebook_id' => 'Facebook ID',
            'acc_facebook_email' => 'Facebook Email',
            'acc_facebook_graph' => 'Facebook Graph',
            'acc_google_id' => 'Google ID',
            'acc_google_email' => 'Google Email',
            'acc_google_token' => 'Google Token',
            'acc_screen_name' => 'Screen Name',
            'acc_cty_id' => 'Country',
            'acc_photo' => 'Photo',
            'acc_created_datetime' => 'Created Datetime',
            'acc_updated_datetime' => 'Updated Datetime',
            'acc_status' => 'Status',
            'acc_tmz_id' => 'Time Zone',
            'acc_birthdate' => 'Birthdate',
            'acc_address' => 'Address',
            'acc_gender' => 'Gender',
        ];
    }
}
