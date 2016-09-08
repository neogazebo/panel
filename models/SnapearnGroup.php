<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
// use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_snapearn_group".
 *
 * @property integer $spg_id
 * @property string $spg_name
 * @property integer $spg_created_by
 * @property integer $spg_created_date
 * @property integer $spg_updated_by
 * @property integer $spg_updated_date
 */
class SnapearnGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_snapearn_group';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    public static function find()
    {
        return new SnapearnGroupQuery(get_called_class());
    }

    // public function behaviors()
    // {
    //     return [
    //         [
    //             'class' => TimestampBehavior::className(),
    //             'attributes' => [
    //                 ActiveRecord::EVENT_BEFORE_INSERT => ['spg_created_date', 'spg_updated_date'],
    //                 ActiveRecord::EVENT_BEFORE_UPDATE => ['spg_updated_date'],
    //             ],
    //             // if you're using datetime instead of UNIX timestamp:
    //             'value' => new \yii\db\Expression('NOW()'),
    //         ],
    //     ];
    // }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spg_created_by', 'spg_created_date', 'spg_updated_by', 'spg_updated_date'], 'integer'],
            [['spg_name'], 'string', 'max' => 255],
            [['spg_name'], 'required', 'on' => 'create'],
            [['spg_name'], 'required', 'on' => 'update'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'spg_created_by']);
    }

    public function getUserUpdate()
    {
        return $this->hasOne(User::className(), ['id' => 'spg_updated_by']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spg_id' => 'ID',
            'spg_name' => 'Name',
            'spg_created_by' => 'Created By',
            'spg_created_date' => 'Created Date',
            'spg_updated_by' => 'Updated By',
            'spg_updated_date' => 'Updated Date',
        ];
    }
}