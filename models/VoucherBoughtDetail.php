<?php

namespace app\models;

/**
 * This is the model class for table "tbl_voucher_bought_detail".
 *
 * @property string $vod_id
 * @property integer $vod_vob_id
 * @property string $vod_sn
 * @property string $vod_code
 * @property integer $vod_expired
 * @property integer $vou_redeemed
 */
class VoucherBoughtDetail extends \yii\db\ActiveRecord
{
    public static function find()
    {
        return new VoucherBoughtDetailQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_voucher_bought_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vod_vob_id', 'vou_redeemed'], 'integer'],
            [['vod_sn', 'vod_code'], 'string', 'max' => 100],
            [['vod_expired'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
	public function getVoucherBought()
    {
        return $this->hasMany(VoucherBought::className(), ['vob_id' => 'vod_vob_id']);
    }
	
	public function getVoucher($id)
	{
		$voucher = Voucher::find()
			->leftJoin('tbl_voucher_bought','vob_vou_id=vou_id')
			->where(['vob_id' => $id])
			->one();
		
		return $voucher;
	}
	
    public function attributeLabels()
    {
        return [
            'vod_id' => 'ID',
            'vod_vob_id' => 'Voucher Bought',
            'vod_sn' => 'Serial No',
            'vod_code' => 'Code',
            'vod_expired' => 'Expired',
            'vou_redeemed' => 'Redeemed',
        ];
    }

}
