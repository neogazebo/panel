<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_logging".
 *
 * @property integer $log_id
 * @property integer $log_usr_id
 * @property integer $log_datetime
 * @property string $log_activity
 * @property string $log_item
 * @property integer $log_item_id
 * @property string $log_description
 */
class Logging extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_logging';
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
            [['log_usr_id', 'log_datetime', 'log_item_id'], 'integer'],
            [['log_activity', 'log_item', 'log_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'ID',
            'log_usr_id' => 'User',
            'log_datetime' => 'Created On',
            'log_activity' => 'Activity',
            'log_item' => 'Item',
            'log_item_id' => 'Item ID',
            'log_description' => 'Description',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'log_usr_id']);
    }

    public function saveLog($activities)
    {
        $model = new Logging;
        $model->log_usr_id = Yii::$app->user->id;
        $model->log_activity = $activities[0];
        $model->log_datetime = \app\components\helpers\Utc::getNow();
        $model->log_description = $activities[1];
        $model->log_item = $activities[2];
        $model->log_item_id = $activities[3];
        $model->save();
    }
}