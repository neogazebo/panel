<?php

namespace app\models;

use Yii;
use app\models\User;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_company_speciality".
 *
 * @property integer $com_spt_id
 * @property string $com_spt_type
 * @property integer $com_spt_multiple_point
 * @property integer $com_spt_created_by
 * @property integer $com_spt_created_date
 * @property integer $com_spt_updated_date
 */
class CompanySpeciality extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_company_speciality';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['com_spt_type', 'com_spt_cty_id', 'com_spt_max_point' ,'com_spt_multiple_point'], 'required'],
            [['com_spt_multiple_point', 'com_spt_created_by', 'com_spt_created_date', 'com_spt_updated_date'], 'integer'],
            [['com_spt_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'com_spt_id' => 'ID',
            'com_spt_cty_id' => 'Country',
            'com_spt_type' => 'Speciality Name',
            'com_spt_max_point' => 'Maximum point',
            'com_spt_multiple_point' => 'Multiple Point',
            'com_spt_created_by' => 'Created By',
            'com_spt_created_date' => 'Created Date',
            'com_spt_updated_date' => 'Updated Date',
        ];
    }

    public function behaviors()
    {
        return [
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['com_spt_created_date'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['com_spt_updated_date'],
                    ],
                ],
                'createdBy' => [
                    'class' => AttributeBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['com_spt_created_by'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['com_spt_created_by'],
                    ],
                    'value' => function($event){
                        return Yii::$app->user->id;
                    }
                ]
            ];
    }

    public function getPic()
    {
        return $this->hasOne(User::className(),['id' => 'com_spt_created_by']);
    }

    public function getPromo()
    {
        $today = time();
        return $this->hasOne(ComSpecialityPromo::className(),[
                'spt_promo_com_spt_id' => 'com_spt_id'
                ])->where('spt_promo_cty_id = com_spt_cty_id')
                ->where('spt_promo_start_date <= :today')
                ->andWhere('spt_promo_end_date >= :today',[
                    ':today' => $today
                ]);
    }

    /**
     * @inheritdoc
     * @return CompanySpecialityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanySpecialityQuery(get_called_class());
    }
}
