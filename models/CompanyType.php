<?php

namespace app\models;

use Yii;
use app\models\User;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_company_type".
 *
 * @property integer $com_type_id
 * @property string $com_type_name
 * @property integer $com_type_multiple_point
 * @property integer $com_type_max_point
 * @property integer $com_type_created_by
 * @property integer $com_type_created_date
 * @property integer $com_type_updated_date
 * @property integer $com_type_deleted_date
 */
class CompanyType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_company_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['com_type_name', 'com_type_max_point','com_type_multiple_point'], 'required'],
            [['com_type_name'],'unique'],
            [['com_type_max_point', 'com_type_created_by', 'com_type_created_date', 'com_type_updated_date', 'com_type_deleted_date'], 'integer'],
            [['com_type_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'com_type_id' => 'Type ID',
            'com_type_name' => 'Speciality Name',
            'com_type_multiple_point' => 'Multiple Point',
            'com_type_max_point' => 'Max Point',
            'com_type_created_by' => 'Created By',
            'com_type_created_date' => 'Created Date',
            'com_type_updated_date' => 'Updated Date',
            'com_type_deleted_date' => 'Deleted Date',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['com_type_created_date'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['com_type_updated_date'],
                        ActiveRecord::EVENT_BEFORE_DELETE => ['com_type_deleted_date'],
                    ],
                ],
                'createdBy' => [
                    'class' => AttributeBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['com_type_created_by'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['com_type_created_by'],
                        ActiveRecord::EVENT_BEFORE_DELETE => ['com_type_created_by'],
                    ],
                    'value' => function($event){
                        return Yii::$app->user->id;
                    }
                ]
        ];
    }   

    public function getPic()
    {
        return $this->hasOne(User::className(),['id' => 'com_type_created_by']);
    }
    /**
     * @inheritdoc
     * @return CompanyTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanyTypeQuery(get_called_class());
    }
}
