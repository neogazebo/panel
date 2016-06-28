<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_working_time".
 *
 * @property integer $wrk_id
 * @property integer $wrk_type
 * @property integer $wrk_by
 * @property integer $wrk_param_id
 * @property integer $wrk_start
 * @property integer $wrk_end
 * @property integer $wrk_time
 * @property string $wrk_description
 * @property integer $wrk_created
 * @property integer $wrk_updated
 */
class WorkingTime extends \yii\db\ActiveRecord
{
    const APPROVED_TYPE = 1;
    const REJECTED_TYPE = 2;
    const POINT_APPROVAL = 1;
    const POINT_ADD_NEW_MERCHANT = 3;
    const CORRECTION_TYPE = 2;
    const UPDATE_TYPE = 1;

    public $total_record;
    public $total_point;
    public $total_rejected;
    public $total_approved;
    public $rejected_rate;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_working_time';
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
            [['wrk_type'], 'required'],
            [['wrk_type', 'wrk_by', 'wrk_param_id', 'wrk_start', 'wrk_end', 'wrk_time', 'wrk_created', 'wrk_updated','wrk_point_type'], 'integer'],
            [['wrk_description'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wrk_id' => 'ID',
            'wrk_type' => 'Type',
            'wrk_by' => 'By',
            'wrk_param_id' => 'Param ID',
            'wrk_start' => 'Start',
            'wrk_end' => 'End',
            'wrk_time' => 'Time',
            'wrk_description' => 'Description',
            'wrk_created' => 'Created',
            'wrk_updated' => 'Updated',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['wrk_created'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['wrk_updated'],
                ],
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::ClassName(),['id' => 'wrk_by']);
    }

    public function getTime($userId)
    {
        $query = self::find()
            ->select('sum(wrk_time) as total_record')
            ->where('wrk_end IS NOT NULL')
            ->andWhere('wrk_by = :id',[
                    ':id' => $userId
                ])
            ->one();
        return $query;
    }

    /**
     * @inheritdoc
     * @return WorkingTimeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkingTimeQuery(get_called_class());
    }
}
