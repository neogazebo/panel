<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_voucher".
 *
 * @property integer $vou_id
 * @property integer $vou_com_id
 * @property string $vou_reward_name
 * @property string $vou_description
 * @property string $vou_image
 * @property integer $vou_value
 * @property integer $vou_valid_start
 * @property integer $vou_valid_end
 * @property string $vou_original_price
 * @property string $vou_discount_price
 * @property integer $vou_max_voucher
 * @property integer $vou_nr_redeemed
 * @property integer $vou_datetime
 * @property string $vou_fineprint
 * @property integer $vou_limit_one_per_customer
 * @property integer $vou_first_time_customers_only
 * @property integer $vou_requires_reservation
 * @property integer $vou_mobile_redemptions_only
 * @property integer $vou_type
 * @property integer $vou_stock_left
 * @property integer $vou_stock_minimum
 * @property integer $vou_epp_id
 */
class Voucher extends \yii\db\ActiveRecord
{
    public $restriction = [];
    public $restriction_name = [];
    public $restriction_checkbox = [];
    public $restriction_templates = [];
    public $unit;
    public $value;
    public $price;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_voucher';
    }

    public static function find()
    {
        return new VoucherQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vou_com_id', 'vou_value', 'vou_max_voucher', 'vou_nr_redeemed', 'vou_limit_one_per_customer', 'vou_first_time_customers_only', 'vou_requires_reservation', 'vou_mobile_redemptions_only', 'vou_type', 'vou_stock_left', 'vou_stock_minimum', 'vou_epp_id', 'vou_mpp_id'], 'integer'],
            [['vou_reward_name', 'vou_com_id', 'vou_value', 'vou_valid_start', 'vou_valid_end'], 'required'],
            [['vou_stock_left'], 'required', 'on' => 'update'],
            [['vou_description', 'vou_fineprint'], 'string'],
            [['vou_original_price', 'vou_discount_price'], 'number'],
            [['vou_reward_name'], 'string', 'max' => 300],
            [['vou_image'], 'string', 'max' => 200],
            [['vou_datetime', 'vou_valid_start', 'vou_valid_end'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vou_id' => 'ID',
            'vou_com_id' => 'Business',
            'vou_reward_name' => 'Reward',
            'vou_description' => 'Description',
            'vou_image' => 'Image',
            'vou_value' => 'Value',
            'vou_valid_start' => 'Start',
            'vou_valid_end' => 'Expired',
            'vou_original_price' => 'Original Price',
            'vou_discount_price' => 'Discount Price',
            'vou_max_voucher' => 'Max Voucher',
            'vou_nr_redeemed' => 'Nr Redeemed',
            'vou_datetime' => 'Created On',
            'vou_updated' => 'Updated On',
            'vou_fineprint' => 'Fineprint',
            'vou_limit_one_per_customer' => 'Limit One Per Customer',
            'vou_first_time_customers_only' => 'First Time Customers Only',
            'vou_requires_reservation' => 'Requires Reservation',
            'vou_mobile_redemptions_only' => 'Mobile Redemptions Only',
            'vou_type' => 'Type',
            'vou_stock_left' => 'Stock Left',
            'vou_stock_minimum' => 'Stock Minimum',
            'vou_epp_id' => 'Epay',
            'vou_mpp_id' => 'Mobile Pulsa',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['vou_datetime'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['vou_updated'],
                ],
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->vou_reward_name = mb_convert_encoding($this->vou_reward_name, 'UTF-8');
            $this->vou_valid_start = strtotime($this->vou_valid_start);
            $this->vou_valid_end = strtotime($this->vou_valid_end);
            $business = Company::findOne($this->vou_com_id);
            if(preg_match('/e-pay/i', $business->com_name)) {
                if($this->vou_type == 0) {
                    $this->vou_epp_id = $this->vou_epp_id;
                    $this->vou_mpp_id = '';
                }
            } elseif(preg_match('/mobile\spulsa/i', $business->com_name)) {
                if($this->vou_type == 0) {
                    $this->vou_epp_id = '';
                    $this->vou_mpp_id = $this->vou_mpp_id;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            RestrictionTemplate::deleteAll('ret_com_id=:id', ['id' => $this->vou_com_id]);
            RestrictionVoucher::deleteAll('rev_vou_id=:id', ['id' => $this->vou_id]);
            return true;
        } else {
            return false;
        }
    }

    public function getImage()
    {
        if (!empty($this->vou_image))
            return Yii::$app->params['businessUrl'] . $this->vou_image;
        return Yii::$app->homeUrl . 'img/90.jpg';
    }

    public function getCompanyList()
    {
        return Company::find()->where('com_type=:type', ['type' => 1])->orderBy('com_id')->all();
    }

    public function getBusiness()
    {
        return $this->hasOne(Company::className(), ['com_id' => 'vou_com_id']);
    }

    public function getProduct()
    {
        return EpayProduct::find()->where('epp_product_type = :type AND epp_gst = :gst', [
            'type' => EpayProduct::TYPE_ONLINE_PIN,
            'gst'=> EpayProduct::GST_INCLUDE
        ])->all();
    }

    public function getBought()
    {
        return $this->hasOne(VoucherBought::className(), ['vob_vou_id' => 'vou_id']);
    }

    public function getEpay()
    {
        return $this->hasOne(Epay::className(), ['epa_id' => 'vou_epp_id']);
    }

    public function getMobilepulsa()
    {
        return MobilePulsaProduct::find()->select([
            'mpp_id',
            'CONCAT(mpp_product_code, " [", mpp_operator, " -> ", mpp_nominal, "]") AS title'
        ])->asArray()->all();
    }

    public function getMobile()
    {
        return $this->hasOne(MobilePulsaProduct::className(), ['mpp_id' => 'vou_mpp_id']);
    }

    public function getRedeem()
    {
        return $this->hasMany(VoucherRedeemed::className(), ['vor_vou_id' => 'vou_id']);
    }

    public function getType()
    {
        return [
            0 => 'Electronic',
            1 => 'Physical',
        ];
    }

    public function getRestrictions()
    {
        ProductItem::find();
        return RestrictionTemplate::find()->where(['ret_com_id' => $this->vou_com_id])->all();
    }

    public function getDefaultRestrictions()
    {
        return [
            'Limited to one (1) per customer',
            'First-time customers only',
            'Requires reservation',
        ];
    }
    
    public function getTotalCurrentMonth()
    {
        if(Yii::$app->user->identity->type == 3) {
            return Voucher::find()
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = vou_com_id')
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW())")
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->count();
        } else {
            return Voucher::find()
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW())")
                ->count();
        }        
    }
    
    public function getTotalLastMonth()
    {
        if(Yii::$app->user->identity->type == 3) {
            return Voucher::find()
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = vou_com_id')
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW()) - 1")
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->count();
        } else {
            return Voucher::find()
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW()) - 1")
                ->count();
        }
    }
    
    public function getTotalRedeemCurrentMonth()
    {
        if(Yii::$app->user->identity->type == 3) {
            return VoucherRedeemed::find()
                ->innerJoin('tbl_voucher c', 'c.vou_id = vor_vou_id')
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id')
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW())")
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->count();
        } else {
            return VoucherRedeemed::find()
                ->where("FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW())")
                ->count();
        }
    }
    
    public function getTotalRedeemLastMonth()
    {
        if(Yii::$app->user->identity->type == 3) {
            return VoucherRedeemed::find()
                ->innerJoin('tbl_voucher c', 'c.vou_id = vor_vou_id')
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id')
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW()) - 1")
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->count();
        } else {
            return VoucherRedeemed::find()
                ->where("FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW()) - 1")
                ->count();
        }
    }

    public function getTotalMemberRedeem()
    {
        // the query : SELECT vor_id FROM tbl_voucher_redeemed GROUP BY vor_mem_id
        if(Yii::$app->user->identity->type == 3) {
            return VoucherRedeemed::find()
                ->innerJoin('tbl_voucher c', 'c.vou_id = vor_vou_id')
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id')
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->groupBy('vor_mem_id')
                ->count();
        } else {
            return VoucherRedeemed::find()
                ->groupBy('vor_mem_id')
                ->count();
        }
    }    

    public function getTotalMemberRedeemThisMonth()
    {
        // SELECT vor_id FROM tbl_voucher_redeemed WHERE FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())
        // AND FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW())
        // GROUP BY vor_mem_id 
        if(Yii::$app->user->identity->type == 3) {
            return VoucherRedeemed::find()
                ->innerJoin('tbl_voucher c', 'c.vou_id = vor_vou_id')
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id')
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW())")
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->groupBy('vor_mem_id')
                ->count();
        } else {
            return VoucherRedeemed::find()
                ->where("FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW())")
                ->groupBy('vor_mem_id')
                ->count();
        }
    }

    public function getTotalMemberRedeemLastMonth()
    {
        // SELECT vor_id FROM tbl_voucher_redeemed WHERE FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())
        // AND FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW()) - 1
        // GROUP BY vor_mem_id 
        if(Yii::$app->user->identity->type == 3) {
            return VoucherRedeemed::find()
                ->innerJoin('tbl_voucher c', 'c.vou_id = vor_vou_id')
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id')
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW()) - 1")
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->groupBy('vor_mem_id')
                ->count();
        } else {
            return VoucherRedeemed::find()
                ->where("FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW())-1")
                ->groupBy('vor_mem_id')
                ->count();
        }
    }

    public function getTotalItemRedeem()
    {
        // the query : SELECT count(vor_id) FROM tbl_voucher_redeemed
        if(Yii::$app->user->identity->type == 3) {
            return VoucherRedeemed::find()
                ->innerJoin('tbl_voucher c', 'c.vou_id = vor_vou_id')
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id')
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->count();
        } else {
            return VoucherRedeemed::find()
                ->count();
        }
    }        

    public function getTotalItemRedeemThisMonth()
    {
        // SELECT COUNT(vor_id) FROM tbl_voucher_redeemed WHERE FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())
        // AND FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW())
        if(Yii::$app->user->identity->type == 3) {
            return VoucherRedeemed::find()
                ->innerJoin('tbl_voucher c', 'c.vou_id = vor_vou_id')
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id')
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW())")
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->count();
        } else {
            return VoucherRedeemed::find()
                ->where("FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW())")
                ->count();
        }
    }

    public function getTotalItemRedeemLastMonth()
    {
        // SELECT COUNT(vor_id) FROM tbl_voucher_redeemed WHERE FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())
        // AND FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW()) - 1
        if(Yii::$app->user->identity->type == 3) {
            return VoucherRedeemed::find()
                ->innerJoin('tbl_voucher c', 'c.vou_id = vor_vou_id')
                ->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id')
                ->where("FROM_UNIXTIME(vou_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vou_datetime, '%m') = MONTH(NOW()) - 1")
                ->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall])
                ->count();
        } else {
            return VoucherRedeemed::find()
                ->where("FROM_UNIXTIME(vor_datetime, '%Y') = YEAR(NOW())")
                ->andWhere("FROM_UNIXTIME(vor_datetime, '%m') = MONTH(NOW()) - 1")
                ->count();
        }
    }    

    public function saveRestrictions(array $data)
    {
        if(!empty($data)) {
            $terms = $data['terms'];
            $termsCheck = $data['terms_check'];
    
            if(!empty($termsCheck)) {
                $selectedTerms = [];
                foreach ($termsCheck as $k => $v) {
                    $selectedTerms[] = $terms[array_search($v, $terms)];
                }
                // clear first
                RestrictionTemplate::deleteAll('ret_com_id=:id', ['id' => $this->vou_com_id]);
                RestrictionVoucher::deleteAll('rev_vou_id=:id', ['id' => $this->vou_id]);
    
                foreach ($selectedTerms as $row) {
                    $resTemp = new RestrictionTemplate();
                    $resTemp->ret_com_id = $this->vou_com_id;
                    $resTemp->ret_description = $row;
                    $resTemp->save();
    
                    $resVou = new RestrictionVoucher();
                    $resVou->rev_vou_id = $this->vou_id;
                    $resVou->rev_ret_id = $resTemp->ret_id;
                    $resVou->save();
                }
            }
        }
    }

}
