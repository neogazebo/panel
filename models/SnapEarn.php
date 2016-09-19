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
 * @property string $sna_ops_receipt_amount
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
 * @property string $sna_address
 */
class SnapEarn extends \yii\db\ActiveRecord
{
    const STATUS_REJECTED = 2;
    const STATUS_APPROVED = 1;
    const LIMIT_RECEIPT = 2;
    public $sna_push;
    public $total_cat = null;
    public $categoryName;
    public $amount;
    public $country;
    public $total_amount;
    public $tanggal;
    public $jumlah;
    public $weeks;
    public $total_unique;
    public $total_unique_user_id;
    public $total_unique_user_my;

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
            [['sna_com_id',
//                'sna_receipt_date',
                'sna_upload_date',
                'sna_cat_id',
                'sna_com_name'],
            'required'],
            [['sna_push'],'safe'],
            [['sna_ops_receipt_amount',
                'sna_ops_receipt_number',
                'sna_receipt_image',
                'sna_transaction_time'], 'required', 'when' => function($model) {
                return $model->sna_status == 1;
            }, 'whenClient' => "function(attribute, value) { return $('.status').val() == 1 }",'on' => 'update'],
            [['sna_com_id',
                'sna_point',
                'sna_status',
                'sna_upload_date',
                'sna_review_date',
                'sna_review_by',
                'sna_sem_id',
                'sna_cat_id',
                // 'sna_cus_id'
                ],
            'integer'],
            [['sna_com_id'],'required','on' => 'add_existing'],
            [['sna_status'],'globalValidation'],
            [['sna_ops_receipt_amount'], 'number', 'min' => 1, 'when' => function($model) {
                return $model->sna_status == 1;
            }, 'whenClient' => "function(attribute, value) { return $('.status').val() == 1 }", 'on' => 'update'],
            [['sna_ops_receipt_number'],
                'string',
                'max' => 35],
            [['sna_ops_receipt_number'],
                'approvePerday',
                'when' => function($model) {
                    return $model->sna_status == 1;
                }, 'whenClient' => "function(attribute, value) { return $('.status').val() == 1 }", 'on' => 'update'],
            [['sna_receipt_date'],
                'string',
                'max' => 10],
            [['sna_ops_receipt_amount'],
                'checkPoint',
                'when' => function($model) {
                    return $model->sna_status == 1;
                },'on' => 'update'],
            [['sna_ops_receipt_number'], 'validateReceipt'],
            [['sna_receipt_image'],
                'string',
                'max' => 75],
            [['sna_com_name','sna_address'],
                'string',
                'max' => 100],
            [['sna_com_id'], 'checkMerchant', 'when' => function($model) {
                return $model->sna_status == 2;
            }, 'whenClient' => "function(attribute, value) { return $('.status').val() == 2 }",'on' => 'update'],
            [['sna_transaction_time',
                'sna_receipt_number',
                'sna_ops_receipt_amount',
                'sna_com_id'], 'safe','on' => 'correction'],
        ];
    }

    public function globalValidation($data)
    {   $m = Company::findOne($this->sna_com_id);
        if (empty($m)) {
           $this->addError($data, Yii::t('app', 'Please create merchant first! Thanks.')); 
        } else {
            if ($this->sna_status == 1) {
                if (($m->com_point - $this->sna_point) < 0 || ($this->sna_point > $m->com_point)) {
                    $this->addError($data, Yii::t('app', 'Points merchant is Not Enough !'));
                }
            }
        }
    }

    public function checkMerchant($data)
    {
        $model = Company::findOne($this->sna_com_id);
        if (empty($model)) {
            $this->addError($data, Yii::t('app', 'Merchant cannot be blank!'));
        }
    }

    public function checkPoint($data)
    {
        $model = Company::findOne($this->sna_com_id);
        if (empty($model)) {
            $this->addError($data, Yii::t('app', 'Please create merchant first! Thanks.'));
        } else {
            $merchant_point = $model->com_point;
            if (($merchant_point - $this->sna_point) < 0 || $this->sna_point > $merchant_point || empty($merchant_point)) {
                $this->addError($data, Yii::t('app', 'Points merchant is Not Enough !'));
            }
        }
    }

    public function approvePerday($data)
    {
        $count = self::find()
            ->where(['sna_acc_id' => $this->sna_acc_id])
            ->andWhere(['date(from_unixtime(sna_transaction_time))' => date('Y-m-d', strtotime($this->sna_transaction_time))])
            ->andWhere(['sna_com_id' => $this->sna_com_id])
            ->andWhere(['<>','sna_transaction_time',0])
            ->count();
        if ($this->sna_status == 1) {
            if ($count >= 2) {
                $this->addError($data, Yii::t('app', "Sorry, only 2 receipt max /day/merchant."));
            }
        }
    }

    public function validateReceipt($data)
    {
        if ($this->sna_status == 1) {
            $query = self::find()
                ->where(['sna_ops_receipt_number' => trim($this->sna_ops_receipt_number)])
                ->andWhere(['sna_com_id' => $this->sna_com_id])
                ->andWhere(['=','sna_status',1])
                ->count();
            if($query >= 1)
                $this->addError($data, Yii::t('app', 'This number receipt has taken'));
        }
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

    public function getStatuscorrection()
    {
        return [1 => 'Approved', 2 => 'Rejected'];
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

    public function getMember()
    {
        return $this->hasOne(Account::className(), ['acc_id' => 'sna_acc_id']);
    }

    public function getBusiness()
    {
        return $this->hasOne(Company::className(), ['com_id' => 'sna_com_id']);
    }

    public function getRemark()
    {
        return $this->hasOne(SnapEarnRemark::className(), ['sem_id' => 'sna_sem_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['cat_id' => 'sna_cat_id']);
    }

    public function getReview()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'sna_review_by']);
    }


    // public function getCustomer()
    // {
    //     return $this->hasOne(CustomerMaster::className(), ['cus_id' => 'sna_cus_id']);
    // }

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
            'sna_ops_receipt_number' => 'Receipt Number',
            'sna_receipt_date' => 'Time',
            'sna_ops_receipt_amount' => 'Amount',
            'sna_point' => 'Points',
            'sna_status' => 'Status',
            'sna_upload_date' => 'Upload Date',
            'sna_review_date' => 'Review Date',
            'sna_review_by' => 'Review By',
            'sna_sem_id' => 'Remark',
            'sna_cat_id' => 'Category',
            'sna_receipt_image' => 'Receipt Image',
            'sna_com_name' => 'Merchant Name',
            'sna_transaction_time' => 'Transaction Time',
            'sna_push' => 'Yes',
            'sna_company_tagging' => 'Tagging'
        ];
    }

    public static function find()
    {
        return new SnapEarnQuery(get_called_class());
    }

    public static function getDashboardModel()
    {
        return new SnapEarnDashboardQuery(get_called_class());
    }
}
