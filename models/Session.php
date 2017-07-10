<?php

namespace app\models;

use Yii;

class Session extends \yii\db\ActiveRecord
{
    CONST WEB = 0;
    CONST MOBILE = 1;

    public static function tableName()
    {
        return 'tbl_session';
    }

    public function rules()
    {
        return [
            [['ses_usr_id', 'ses_activity', 'ses_valid', 'ses_create_datetime', 'ses_app_type', 'ses_key', 'ses_last_ip'], 'safe'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ses_last_ip = $_SERVER['REMOTE_ADDR'];
            $this->ses_app_type = self::WEB;
            $this->ses_activity = time();
            $this->ses_valid = strtotime("+1 week");
            if ($this->isNewRecord) {
                $this->ses_create_datetime = time();
            }
            return true;
        }
        return false;
    }

    public function attributeLabels()
    {
        return [
            'ses_id' => 'ID',
            'ses_usr_id' => 'User',
            'ses_key' => 'Key',
            'ses_activity' => 'Activity',
            'ses_valid' => 'Valid',
            'ses_last_ip' => 'Last Ip',
            'ses_create_datetime' => 'Create At',
            'ses_app_type' => 'App Type',
        ];
    }

    public static function add($userId)
    {
        $model = new Session();
        $model->ses_usr_id = $userId;
        $model->ses_key = sha1($userId . time());

        Yii::$app->session->set('ebz_session_key', $model->ses_key);

        if ($model->save())
            return $model;
        return false;
    }

    public static function check($userId, $key)
    {
        $model = Session::findOne([
                    'ses_usr_id' => $userId,
                    'ses_key' => $key
        ]);
        if ($model && $model->ses_valid > time()) {
            if ($model->save()) {
                return true;
            }
        }
        return false;
    }

}
