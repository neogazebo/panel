<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_activity_sent".
 *
 * @property integer $acs_id
 * @property integer $acs_acv_id
 * @property integer $acs_acc_id
 * @property integer $acs_read
 * @property integer $acs_hide
 * @property integer $acs_android_pushed
 * @property integer $acs_datetime
 * @property string $acs_custom_data
 */
class ActivitySent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_activity_sent';
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
            [['acs_acv_id', 'acs_acc_id', 'acs_read', 'acs_hide', 'acs_android_pushed', 'acs_datetime'], 'integer'],
            [['acs_custom_data'], 'required'],
            [['acs_custom_data'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'acs_id' => 'ID',
            'acs_acv_id' => 'Activity',
            'acs_acc_id' => 'Account',
            'acs_read' => 'Read',
            'acs_hide' => 'Hide',
            'acs_android_pushed' => 'Android Pushed',
            'acs_datetime' => 'Datetime',
            'acs_custom_data' => 'Custom Data',
        ];
    }
}
