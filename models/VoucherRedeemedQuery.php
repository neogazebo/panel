<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class VoucherRedeemedQuery extends ActiveQuery
{
    public $total;

    public function totalRedeemmed()
    {
        $this->leftJoin('tbl_voucher c', 'c.vou_id = vor_vou_id');
        $this->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = c.vou_com_id');
        $this->where('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall]);
        $this->count();
        return $this;
    }
    
    public function getList()
    {
        $this->leftJoin('tbl_member', 'mem_id = vor_mem_id');
        $this->innerJoin('tbl_voucher', 'vou_id = vor_vou_id');
        $this->innerJoin('tbl_voucher_bought_detail', 'vod_id = vor_vod_id');
        $this->innerJoin('tbl_redemption_partner', 'red_id = vor_red_id');
        $this->leftJoin('tbl_user', 'usr_id = mem_usr_id');
        $this->innerJoin('tbl_company', 'com_id = vou_com_id');
        if(isset($_GET['search'])) {
            $this->andWhere('
                vor_trx_id = :trx OR 
                mem_screen_name LIKE :get OR 
                vou_reward_name LIKE :get
            ', [
                ':trx' => $_GET['search'],
                ':get' => '%' . $_GET['search'] . '%'
            ]);
        }
        return $this;
    }

    public function getCitro()
    {
        if(isset($_GET['search'])) {
            $this->leftJoin('tbl_member b', 'b.mem_id = vor_mem_id');
            $this->leftJoin('tbl_voucher c', 'c.vou_id = vor_vou_id');
            $this->andWhere(['LIKE', 'vor_trx_id', $_GET['search']]);
            $this->orWhere(['LIKE', 'b.mem_screen_name', $_GET['search']]);
            $this->orWhere(['LIKE', 'c.vou_reward_name', $_GET['search']]);
        }
        $this->andWhere(['vor_red_id' => 2]);
        return $this;
    }

    public function getManisList()
    {
        $this->leftJoin('tbl_voucher b', 'b.vou_id = vor_vou_id');
        $this->leftJoin('tbl_voucher_bought_detail c', 'c.vod_id = vor_vod_id');
        $this->leftJoin('tbl_member d', 'd.mem_id = vor_mem_id');
        $this->innerJoin('tbl_redemption_partner e', 'e.red_id = vor_red_id');
        if(isset($_GET['search'])) {
            $this->andWhere('
                vor_trx_id LIKE :get OR 
                b.vou_reward_name LIKE :get OR 
                c.vod_sn LIKE :get OR 
                c.vod_code LIKE :get OR 
                d.mem_screen_name LIKE :get 
            ', [
                ':get' => '%' . $_GET['search'] . '%'
            ]);
        }
        $this->orderBy('b.vou_datetime DESC');
        return $this;
    }
    
    public function getCitroList()
    {
        $this->leftJoin('tbl_voucher b', 'b.vou_id = vor_vou_id');
        $this->leftJoin('tbl_voucher_bought_detail c', 'c.vod_id = vor_vod_id');
        $this->leftJoin('tbl_member d', 'd.mem_id = vor_mem_id');
        if(isset($_GET['search'])) {
            $this->andWhere('
                vor_trx_id LIKE :get OR 
                b.vou_reward_name LIKE :get OR 
                c.vod_sn LIKE :get OR 
                c.vod_code LIKE :get OR 
                d.mem_screen_name LIKE :get 
            ', [
                ':get' => '%' . $_GET['search'] . '%'
            ]);
        }
        $this->andWhere(['vor_red_id' => 2]);
        $this->orderBy('b.vou_datetime DESC');
        return $this;
    }
    
    public function redeemedItem(){
        $this->innerJoin('tbl_voucher c','c.vou_id = vor_vou_id');
        $this->innerJoin('tbl_voucher_bought_detail d','d.vod_id = vor_vod_id');
        $this->leftJoin('tbl_redemption_partner e','red_id = vor_red_id');
        $this->leftJoin('tbl_epay_detail f','f.epd_id = vor_epd_id');
        return $this;
    }

    public function getTotalMember()
    {
        $query = '
            SELECT COUNT(DISTINCT mem_id) AS `total` 
            FROM tbl_voucher_redeemed 
            LEFT JOIN tbl_member ON mem_id = vor_mem_id 
            INNER JOIN tbl_voucher ON vou_id = vor_vou_id 
            LEFT JOIN tbl_user ON usr_id = mem_usr_id 
        ';
        if(isset($_GET['search'])) {
            $query .= '
                WHERE 
                vor_trx_id = "' . $_GET['search'] . '" OR 
                mem_screen_name LIKE "%' . $_GET['search'] . '%" OR 
                vou_reward_name LIKE "%' . $_GET['search'] . '%"
            ';
        }
        $command = Yii::$app->db->createCommand($query)->queryOne();
        return $command;
    }

}
