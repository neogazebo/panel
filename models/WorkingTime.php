<?php

namespace app\models;

use Yii;

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
    const SNAPEARN_TYPE = 1;
    const MERCHANT_TYPE = 2;

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
            [['wrk_type', 'wrk_by', 'wrk_param_id', 'wrk_start', 'wrk_end', 'wrk_time', 'wrk_created', 'wrk_updated'], 'integer'],
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

    /**
     * @inheritdoc
     * @return WorkingTimeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkingTimeQuery(get_called_class());
    }
}
