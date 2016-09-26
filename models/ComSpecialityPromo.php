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
 * @property integer $spt_promo_multiple_point
 * @property integer $spt_promo_created_by
 * @property integer $spt_promo_start_date
 * @property integer $spt_promo_end_date
 * @property integer $spt_promo_created_date
 */
class ComSpecialityPromo extends ActiveRecord
{
    public $end_date;
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
            [['spt_promo_com_spt_id', 'spt_promo_description', 'spt_promo_multiple_point', 'spt_promo_start_date', 'spt_promo_end_date'], 'required'],
            [['spt_promo_start_date'],'checkDate'],
            [['spt_promo_end_date'],'checkEndDate'],
            [['spt_promo_com_spt_id', 'spt_promo_created_by', 'spt_promo_created_date','spt_promo_max_point'], 'integer'],
            [['end_date'],'safe'],
            [['spt_promo_description','spt_promo_day_promo'], 'string', 'max' => 255],
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
            'spt_promo_multiple_point' => 'Point',
            'spt_promo_created_by' => 'Promo Created By',
            'spt_promo_start_date' => 'Promo Start Date',
            'spt_promo_end_date' => 'Promo End Date',
            'spt_promo_created_date' => 'Promo Created Date',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['spt_promo_created_date']
                ],
            ],
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
        $spt_id = $this->spt_promo_com_spt_id;
        $last_promo = self::find()
            ->select('date(from_unixtime(spt_promo_end_date)) as end_date')
            ->where('spt_promo_com_spt_id = :spt_id',[':spt_id' => $spt_id])
            ->orderBy('spt_promo_id DESC')->limit(1)->asArray()->one();

        if ($start_date < $last_promo['end_date']) {
            $this->addError($data, Yii::t('app', "Start date must greater than by last promo date"));
        }

        if ($start_date < $today) {
            $this->addError($data, Yii::t('app', "Start date must greater than by today"));
        }
    }

    public function checkEndDate($data)
    {
        $end_date = $this->spt_promo_end_date;
        $start_date = $this->spt_promo_start_date;
        if ($end_date < $start_date) {
            $this->addError($data, Yii::t('app', "End date must greater than by Start date"));
        }
    }

    public function getSpeciality()
    {
        return $this->hasOne(CompanySpeciality::className(),['com_spt_id' => 'spt_promo_com_spt_id']);
    }

    public function getPic()
    {
        return $this->hasOne(User::className(),['id' => 'spt_promo_created_by']);
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
