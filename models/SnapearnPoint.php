<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_snapearn_point".
 *
 * @property integer $spo_id
 * @property string $spo_name
 * @property integer $spo_point
 * @property integer $spo_created_by
 * @property integer $spo_created_date
 * @property integer $spo_updated_by
 * @property integer $spo_updated_date
 */
class SnapearnPoint extends \yii\db\ActiveRecord
{
    public $activity;
    public $total_point;
    public $total_time;
//    public $total_
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_snapearn_point';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['spo_created_date', 'spo_updated_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['spo_updated_date'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spo_point', 'spo_created_by', 'spo_created_date', 'spo_updated_by', 'spo_updated_date'], 'integer'],
            [['spo_name', 'spo_point'], 'required'],
            [['spo_name'], 'string', 'max' => 255],
        ];
    }

    public function getUserCreated()
    {
        return $this->hasOne(User::className(), ['id' => 'spo_created_by']);
    }
    
    public function getWorker()
    {
        return $this->hasOne(WorkingTime::className(), ['wrk_rjct_number' => 'spo_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spo_id' => 'ID',
            'spo_name' => 'Name',
            'spo_point' => 'Point',
            'spo_created_by' => 'Created By',
            'spo_created_date' => 'Created Date',
            'spo_updated_by' => 'Updated By',
            'spo_updated_date' => 'Updated Date',
        ];
    }
    
    public static function find()
    {
        return new SnapearnPointQuery(get_called_class());
    }
}