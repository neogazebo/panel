<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_loyalty_point_history".
 *
 * @property integer $lph_id
 * @property integer $lph_parent
 * @property integer $lph_acc_id
 * @property integer $lph_com_id
 * @property integer $lph_cus_id
 * @property integer $lph_lpe_id
 * @property string $lph_param
 * @property integer $lph_amount
 * @property string $lph_type
 * @property string $lph_free
 * @property integer $lph_datetime
 * @property integer $lph_total_point
 * @property integer $lph_expired
 * @property integer $lph_current_point
 * @property integer $lph_approve
 * @property integer $lph_lpt_id
 */
class LoyaltyPointHistory extends \yii\db\ActiveRecord
{
    public $lph_member;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_loyalty_point_history';
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
            [['lph_parent', 'lph_acc_id', 'lph_com_id', 'lph_cus_id', 'lph_lpe_id', 'lph_amount', 'lph_datetime', 'lph_total_point', 'lph_expired', 'lph_current_point', 'lph_approve', 'lph_lpt_id'], 'integer'],
            [['lph_param'], 'required'],
            [['lph_type', 'lph_free'], 'string'],
            [['lph_cus_id','lph_free'],'safe','on'=>'snapEarnUpdate'],
            [['lph_param'], 'string', 'max' => 20],
            [['lph_description'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lph_id' => 'ID',
            'lph_parent' => 'Parent',
            'lph_acc_id' => 'Account ID',
            'lph_com_id' => 'Merchant ID',
            'lph_cus_id' => 'Cus ID',
            'lph_lpe_id' => 'Type',
            'lph_param' => 'Param',
            'lph_amount' => 'Amount',
            'lph_type' => 'Type',
            'lph_free' => 'Free',
            'lph_datetime' => 'Redeemed Time',
            'lph_total_point' => 'Total Point',
            'lph_expired' => 'Expired',
            'lph_current_point' => 'Current Point',
            'lph_approve' => 'Approve',
            'lph_lpt_id' => 'Lpt ID',
            'lph_description' => 'Description',
            'lph_member' => 'Account'
        ];
    }

    public function getOffer()
    {
        return Deal::find()
            ->where('del_id = :id', [':id' => $this->lph_param])
            ->one();
    }

    public function getVoucher()
    {
        return Voucher::find()
            ->where('vou_id = :id', [':id' => $this->lph_param])
            ->one();
    }

    public function getEvent()
    {
        return Event::find()
            ->where('evt_id = :id', [':id' => $this->lph_param])
            ->one();
    }

    public function getUser()
    {
        return Account::find()
            ->where('acc_id = :id', [':id' => $this->lph_param])
            ->one();
    }

    public function getRedeem()
    {
        return VoucherRedeemed::find()
            ->where('vor_id = :id', [':id' => $this->lph_param])
            ->one();
    }

    public function getMember()
    {
        return $this->hasOne(Account::className(), ['acc_id' => 'lph_acc_id']);
    }

    public function getMerchant()
    {
        return $this->hasOne(Company::className(), ['com_id' => 'lph_com_id']);
    }

    public function getType()
    {
        return $this->hasOne(LoyaltyPointType::className(),['lpe_id' => 'lph_lpe_id']);
    }
    /**
     * @inheritdoc
     * @return LoyaltyPointHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LoyaltyPointHistoryQuery(get_called_class());
    }
    
    
    public function test()
    {
        return \Yii::$app->db->createCommand('SELECT com_name, lph_parent FROM manisv3_db.tbl_loyalty_point_history LEFT JOIN manisv3_db.tbl_account ac ON ac.acc_id = lph_acc_id LEFT JOIN tbl_company a ON a.com_id = lph_com_id WHERE lph_acc_id = 2')->queryAll();
    }
    
}
