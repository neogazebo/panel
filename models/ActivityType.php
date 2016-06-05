<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_activity_type".
 *
 * @property integer $act_id
 * @property string $act_language
 * @property integer $act_type
 * @property string $act_name
 * @property string $act_title
 * @property string $act_text
 * @property integer $act_datetime
 * @property string $act_notification
 * @property string $act_push
 * @property string $act_col1
 * @property string $act_col2
 * @property string $act_col3
 * @property string $act_col4
 * @property string $act_col5
 * @property string $act_col6
 * @property string $act_col7
 * @property string $act_col8
 * @property string $act_col9
 * @property string $act_col10
 */
class ActivityType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_activity_type';
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
            [['act_language', 'act_text'], 'required'],
            [['act_type', 'act_datetime'], 'integer'],
            [['act_text', 'act_notification', 'act_push'], 'string'],
            [['act_language'], 'string', 'max' => 5],
            [['act_name'], 'string', 'max' => 200],
            [['act_title', 'act_col1', 'act_col2', 'act_col3', 'act_col4', 'act_col5', 'act_col6', 'act_col7', 'act_col8', 'act_col9', 'act_col10'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'act_id' => 'ID',
            'act_language' => 'Language',
            'act_type' => 'Type',
            'act_name' => 'Name',
            'act_title' => 'Title',
            'act_text' => 'Text',
            'act_datetime' => 'Datetime',
            'act_notification' => 'Notification',
            'act_push' => 'Push',
            'act_col1' => 'Col1',
            'act_col2' => 'Col2',
            'act_col3' => 'Col3',
            'act_col4' => 'Col4',
            'act_col5' => 'Col5',
            'act_col6' => 'Col6',
            'act_col7' => 'Col7',
            'act_col8' => 'Col8',
            'act_col9' => 'Col9',
            'act_col10' => 'Col10',
        ];
    }
}
