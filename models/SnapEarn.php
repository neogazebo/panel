<?php

namespace app\models;

use Yii;
use app\components\helpers\Html;

/**
 * This is the model class for table "tbl_snapearn".
 *
 * @property integer $sna_id
 * @property integer $sna_acc_id
 * @property integer $sna_com_id
 * @property string $sna_receipt_number
 * @property string $sna_receipt_date
 * @property string $sna_receipt_amount
 * @property integer $sna_point
 * @property integer $sna_status
 * @property integer $sna_upload_date
 * @property integer $sna_approved_datetime
 * @property integer $sna_approved_by
 * @property integer $sna_rejected_datetime
 * @property integer $sna_rejected_by
 * @property integer $sna_sem_id
 * @property integer $sna_cat_id
 * @property string $sna_receipt_image
 * @property string $sna_com_name
 */
class SnapEarn extends \yii\db\ActiveRecord
{
    public $sna_push;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_snapearn';
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
            [['sna_acc_id', 'sna_com_id', 'sna_receipt_date', 'sna_upload_date', 'sna_cat_id', 'sna_com_name'], 'required'],
            [['sna_receipt_amount','sna_receipt_number', 'sna_receipt_image'],'required','when' => function($model) {
                return $model->sna_status == 1;
            }, 'whenClient' => "function(attribute, value) { return $('.status').val() == 1 }"],
            [['sna_com_id', 'sna_point', 'sna_status', 'sna_upload_date', 'sna_approved_datetime', 'sna_approved_by', 'sna_rejected_datetime', 'sna_rejected_by', 'sna_sem_id', 'sna_cat_id','sna_receipt_amount'], 'integer'],
            [['sna_receipt_number'], 'string', 'max' => 20],
            [['sna_receipt_date'], 'string', 'max' => 10],
            [['sna_receipt_amount'], 'checkPoint', 'when' => function($model) {
                return $model->sna_status == 1;
            }],
            [['sna_receipt_image'], 'string', 'max' => 75],
            [['sna_com_name'], 'string', 'max' => 100],
            ['sna_transaction_time', 'safe']
        ];
    }

    public function getImage()
    {
        if (!empty($this->sna_receipt_image))
            return Yii::$app->params['businessUrl'] . 'receipt/' . $this->sna_receipt_image;
        return Yii::$app->homeUrl . 'img/90.jpg';
    }

    public function getMerchant()
    {
        return $this->hasOne(Company::className(), ['com_id' => 'sna_com_id']);
    }

    public function getStatus()
    {
        return [0 => 'New', 1 => 'Approved', 2 => 'Rejected'];
    }

    public function getEmail()
    {
        $model = SnapEarnRemark::find()->all();
        return Html::listData($model, 'sem_id', 'sem_remark');
    }

    public function getCompany()
    {
        $model = new Company();
        $model->setScenario('point');
        return $model;
    }

    public function getNewSuggestion()
    {
        $model = CompanySuggestion::find()
            ->where('cos_sna_id = :id', [':id' => $this->sna_id])
            ->one();
        return $model;
    }

    public function checkPoint($data)
    {
        $model = Company::findOne($this->sna_com_id);
        $merchant_point = $model->com_point;
        if (($merchant_point - $this->sna_point) < 0 || $this->sna_point > $merchant_point) {
            $this->addError($data, Yii::t('app', 'Points merchant is Not Enough !'));
        } elseif(empty($merchant_point)) {
            $this->addError($data, Yii::t('app', 'This merchant point must be greater than zero !'));
        }
    }

    public function getMember()
    {
        return $this->hasOne(Account::className(), ['acc_id' => 'sna_acc_id']);
    }

    public function getRemark()
    {
        return $this->hasOne(SnapEarnRemark::className(), ['sem_id' => 'sna_sem_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['cat_id' => 'sna_cat_id']);
    }

    public function getAdminApproved()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'sna_approved_by']);
    }

    public function getAdminRejected()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'sna_rejected_by']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sna_id' => 'ID',
            'sna_acc_id' => 'Member',
            'sna_com_id' => 'Merchant ID',
            'sna_receipt_number' => 'Receipt Number',
            'sna_receipt_date' => 'Receipt Date',
            'sna_receipt_amount' => 'Receipt Amount',
            'sna_point' => 'Point',
            'sna_status' => 'Status',
            'sna_upload_date' => 'Upload Date',
            'sna_approved_datetime' => 'Approved Datetime',
            'sna_approved_by' => 'Approved By',
            'sna_rejected_datetime' => 'Rejected Datetime',
            'sna_rejected_by' => 'Rejected By',
            'sna_sem_id' => 'Remark',
            'sna_cat_id' => 'Category',
            'sna_receipt_image' => 'Receipt Image',
            'sna_com_name' => 'Merchant Name',
            'sna_transaction_time' => 'Transaction Time',
        ];
    }

    public static function find()
    {
        return new SnapEarnQuery(get_called_class());
    }
}
