<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_account_device".
 *
 * @property integer $adv_id
 * @property integer $adv_acc_id
 * @property integer $adv_dvc_id
 * @property integer $adv_last_access
 * @property integer $adv_last_login
 * @property integer $adv_last_logout
 * @property string $adv_last_ip
 * @property double $adv_last_latitude
 * @property double $adv_last_longitude
 * @property integer $adv_active
 */
class AccountDevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_account_device';
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
            [['adv_acc_id', 'adv_dvc_id', 'adv_last_access', 'adv_last_login', 'adv_last_logout'], 'required'],
            [['adv_acc_id', 'adv_dvc_id', 'adv_last_access', 'adv_last_login', 'adv_last_logout', 'adv_active'], 'integer'],
            [['adv_last_latitude', 'adv_last_longitude'], 'number'],
            [['adv_last_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'adv_id' => 'Adv ID',
            'adv_acc_id' => 'Adv Acc ID',
            'adv_dvc_id' => 'Adv Dvc ID',
            'adv_last_access' => 'Adv Last Access',
            'adv_last_login' => 'Adv Last Login',
            'adv_last_logout' => 'Adv Last Logout',
            'adv_last_ip' => 'Adv Last Ip',
            'adv_last_latitude' => 'Adv Last Latitude',
            'adv_last_longitude' => 'Adv Last Longitude',
            'adv_active' => 'Adv Active',
        ];
    }

    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['dvc_id' => 'adv_dvc_id']);
    }

    /**
     * @inheritdoc
     * @return AccountDeviceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountDeviceQuery(get_called_class());
    }
}
