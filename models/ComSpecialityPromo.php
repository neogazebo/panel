<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_com_speciality_promo".
 *
 * @property integer $spt_promo_id
 * @property integer $spt_promo_com_spt_id
 * @property string $spt_promo_description
 * @property integer $spt_promo_point
 * @property integer $spt_promo_created_by
 * @property integer $spt_promo_start_date
 * @property integer $spt_promo_end_date
 * @property integer $spt_promo_created_date
 */
class ComSpecialityPromo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_com_speciality_promo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spt_promo_com_spt_id', 'spt_promo_description', 'spt_promo_point', 'spt_promo_created_by', 'spt_promo_start_date', 'spt_promo_end_date', 'spt_promo_created_date'], 'required'],
            [['spt_promo_start_date'],'checkDate'],
            [['spt_promo_com_spt_id', 'spt_promo_point', 'spt_promo_created_by', 'spt_promo_created_date'], 'integer'],
            [['spt_promo_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spt_promo_id' => 'Promo',
            'spt_promo_com_spt_id' => 'Company Speciality',
            'spt_promo_description' => 'Promo Description',
            'spt_promo_point' => 'Point',
            'spt_promo_created_by' => 'Promo Created By',
            'spt_promo_start_date' => 'Promo Start Date',
            'spt_promo_end_date' => 'Promo End Date',
            'spt_promo_created_date' => 'Promo Created Date',
        ];
    }

    public function behaviors()
    {
        return [
                'createdBy' => [
                    'class' => AttributeBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['spt_promo_created_by'],
                    ],
                    'value' => function($event){
                        return Yii::$app->user->id;
                    }
                ]
            ];
    }

    public function checkDate($data)
    {
        $today = date('Y-m-d');
        $start_date = $this->spt_promo_start_date;
        if ($start_date < $today) {
            $this->addError($data, Yii::t('app', "Start date must greater than by today"));
        }
    }

    public function getSpeciality()
    {
        return $this->hasOne(CompanySpeciality::className(),['com_spt_id' => 'spt_promo_com_spt_id']);
    }

    /**
     * @inheritdoc
     * @return ComSpecialityPromoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ComSpecialityPromoQuery(get_called_class());
    }
}
