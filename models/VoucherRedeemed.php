<?php

namespace app\models;

use Yii;

class VoucherRedeemed extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_voucher_redeemed';
    }

    public static function find()
    {
        return new VoucherRedeemedQuery(get_called_class());
    }

   
    public function rules()
    {
        return [
            [['vor_vou_id', 'vor_vod_id', 'vor_mem_id', 'vor_datetime', 'vor_red_id', 'vor_epd_id'], 'integer'],
            [['vor_remark'], 'string', 'max' => 250],
            [['vor_trx_id', 'vor_msisdn'], 'string', 'max' => 50]
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'vor_id' => 'ID',
            'vor_vou_id' => 'Voucher',
            'vor_vod_id' => 'Bought Detail',
            'vor_mem_id' => 'Member',
            'vor_datetime' => 'Created On',
            'vor_remark' => 'Remark',
            'vor_red_id' => 'Partner',
            'vor_trx_id' => 'Transaction',
            'vor_msisdn' => 'MSISDN',
            'vor_epd_id' => 'Epay Detail',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['mem_id' => 'vor_mem_id']);
    }

    public function getBought()
    {
        return $this->hasOne(VoucherBoughtDetail::className(), ['vod_id' => 'vor_vod_id']);
    }

    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['vou_id' => 'vor_vou_id']);
    }

    public function getPartner()
    {
        return $this->hasOne(RedemptionPartner::className(), ['red_id' => 'vor_red_id']);
    }

    public function getEpay()
    {
        return $this->hasOne(EpayDetail::className(), ['epd_id' => 'vor_epd_id']);
    }

}
