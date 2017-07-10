<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_devices".
 *
 * @property integer $dvc_id
 * @property string $dvc_device_id
 * @property string $dvc_model
 * @property string $dvc_os_version
 * @property integer $dvc_platform
 * @property string $dvc_msisdn
 * @property string $dvc_imei
 * @property string $dvc_imsi
 * @property string $dvc_token
 * @property string $dvc_imsi_operator
 * @property string $dvc_public_key
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_devices';
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
            [['dvc_device_id', 'dvc_model', 'dvc_os_version', 'dvc_public_key'], 'required'],
            [['dvc_platform'], 'integer'],
            [['dvc_public_key'], 'string'],
            [['dvc_device_id'], 'string', 'max' => 40],
            [['dvc_model', 'dvc_imsi', 'dvc_imsi_operator'], 'string', 'max' => 50],
            [['dvc_os_version'], 'string', 'max' => 11],
            [['dvc_msisdn'], 'string', 'max' => 16],
            [['dvc_imei'], 'string', 'max' => 18],
            [['dvc_token'], 'string', 'max' => 255],
            [['dvc_device_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dvc_id' => 'Dvc ID',
            'dvc_device_id' => 'Dvc Device ID',
            'dvc_model' => 'Dvc Model',
            'dvc_os_version' => 'Dvc Os Version',
            'dvc_platform' => 'Dvc Platform',
            'dvc_msisdn' => 'Dvc Msisdn',
            'dvc_imei' => 'Dvc Imei',
            'dvc_imsi' => 'Dvc Imsi',
            'dvc_token' => 'Dvc Token',
            'dvc_imsi_operator' => 'Dvc Imsi Operator',
            'dvc_public_key' => 'Dvc Public Key',
        ];
    }

    /**
     * @inheritdoc
     * @return DeviceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DeviceQuery(get_called_class());
    }
}
