<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class FeatureSubscription extends \yii\db\ActiveRecord {
    
    CONST MANAGER_BASIC = 6;
    CONST MANAGER_PRO1 = 7;
    CONST MANAGER_PRO2 = 8;
    
    CONST STATUS_INACTIVE = 0;
    CONST STATUS_ACTIVE = 1;
    CONST STATUS_SUSPEND = 2;
    CONST STATUS_BLOCK = 3;
    

    public $integrate_chargebee;

    public static function find() {
        return new FeatureSubscriptionQuery(get_called_class());
    }

    public static function tableName() {
        return 'tbl_feature_subscription';
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->fes_name = mb_convert_encoding($this->fes_name, 'UTF-8');
            $this->fes_description = mb_convert_encoding($this->fes_description, 'UTF-8');
            return true;
        }
        return false;
    }

    public function rules() {
        return [
            [['fes_code', 'fes_name'], 'required'],
            [['fes_price', 'fes_price_before_tax', 'fes_gst_tax'], 'number'],
            [['fes_trial', 'fes_status', 'fes_datetime', 'fes_valid_day', 'fes_order','fes_hide'], 'integer'],
            [['fes_code','fes_downgrade_to'], 'string', 'max' => 50],
            [['fes_name'], 'string', 'max' => 200],
            [['fes_currency'], 'string', 'max' => 10],
            [['fes_description'], 'string', 'max' => 255],
            [['fes_cpl_id'], 'string', 'max' => 105]
        ];
    }

    public function attributeLabels() {
        return [
            'fes_id' => 'ID',
            'fes_code' => 'Code',
            'fes_downgrade_to' => 'Downgrade to Package',
            'fes_name' => 'Name',
            'fes_currency' => 'Currency',
            'fes_price' => 'Price',
            'fes_price_before_tax' => 'Price Exclusive',
            'fes_gst_tax' => 'Gst Tax',
            'fes_trial' => 'is Free Trial ?',
            'fes_status' => 'Status',
            'fes_datetime' => 'Datetime',
            'fes_description' => 'Description',
            'fes_valid_day' => 'Valid Day',
            'fes_order' => 'Order',
            'fes_hide' => 'Hidden Package',
            'fes_tm' => 'Telkom Malaysia',
            'fes_cpl_id' => 'Chargebee Plan',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['fes_datetime'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['fes_datetime'],
                ],
            ],
        ];
    }

    public function getDetailPackage() {
        return $this->hasMany(FeatureSubscriptionInfo::className(), ['fsi_fes_code' => 'fes_code']);
    }

    public static function packageList($fes_code) {
        $fes = $fes_code == 'EBC' ? 0 : 1;
        $model = self::find()
            ->where('fes_tm = :fes', [':fes' => $fes])
            ->all();
        $html = '';
        foreach ($model as $row) {
            $html .= '<option value="' . $row->fes_code . '">' . $row->fes_name . '</option>';
        }
        return $html;
    }

    public static function getTmPackage($fesCode = 'FUL') {
        return FeatureSubscription::findOne([
            'fes_code' => $fesCode,
        ]);
    }

    public static function subscripTm1() {
        return FeatureSubscription::find()->where('fes_tm=:tm', [':tm' => 1])->all();
    }

    public function getAmount() {
        if ($this->fes_price == 0)
            return 'FREE';
        else {
            return 'RM ' . $this->fes_price . '<div class="caption">per month</div>';
        }
    }

    public static function packageListData() {
        $model = FeatureSubscription::find()->where('fes_tm = 0')->all();
        return \yii\helpers\ArrayHelper::map($model, 'fes_code', 'fes_name');
    }

    /* tolong jangan dihapus, ini baru ditambahin lagi setelah ada yg menghapus :) -- tajhul */

    public static function packageTmListData($trial = false) {
        $model = FeatureSubscription::find()->where('fes_tm = 1 AND fes_trial=:trial AND fes_hide=0', ['trial' => ($trial) ? 1 : 0])->all();
        return \yii\helpers\ArrayHelper::map($model, 'fes_code', 'fes_name');
    }

    public static function packageRhbListData($trial = false) {
        $model = FeatureSubscription::find()->where('fes_tm = 2 AND fes_trial=:trial', ['trial' => ($trial) ? 1 : 0])->all();
        return \yii\helpers\ArrayHelper::map($model, 'fes_code', 'fes_name');
    }

    public static function packageSkipPayment() {
        return [
            'MNGPRO1',
            'TRIAL-MNGBSC',
            'TRIAL-MNGPRO1',
        ];
    }

    /**
    * Check if this subcription company is skipped payment packages
    * for now skipped payment just applied for : PRO1, TRIAL PRO1, & TRIAL BASIC 
    */
    public static function skipPayment($model) {
        $company = Company::find()->where('com_id=:id',['id'=>$model->com_id])->one();
            if ($company->com_registered_to = User::REGISTERED_TO_TM || $company->com_registered_to = User::REGISTERED_TO_POS 
                    || $company->com_registered_to = User::REGISTERED_TO_MGR) {
            $subscription_code = $company->mySubscription->featureSubscription->fes_code;
            if (in_array($subscription_code, FeatureSubscription::packageSkipPayment()))
                return true;
            return false;
        }
        return false;
    }
    
    public static function nonTrialPackege()
    {
        return [self::MANAGER_BASIC, self::MANAGER_PRO1, self::MANAGER_PRO2];
    }

}
