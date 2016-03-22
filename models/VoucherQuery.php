<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class VoucherQuery extends ActiveQuery {

    public function getList()
    {
    	if(isset($_GET['search'])) {
            $this->leftJoin('tbl_company', 'com_id = vou_com_id');
            $this->where('
                vou_reward_name LIKE :get OR 
                vou_description LIKE :get OR 
                vou_value LIKE :get OR 
                com_name LIKE :get 
            ', [
                ':get' => '%' . $_GET['search'] . '%'
            ]);
    	}
        return $this;
    }

    public function getVoucherBought($id)
    {
        $this->leftJoin('tbl_voucher_bought', 'vob_vou_id = vou_id');
        $this->leftJoin('tbl_voucher_bought_detail', 'vod_vob_id = vob_id');
        $this->where('vou_id = :id', [':id' => $id]);
        return $this;
    }

    public function getVouchers($date)
    {
        if(Yii::$app->user->identity->type == 3) {
            $this->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = vou_com_id');
            $this->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall]);
        }
        $range = explode(" - ", $date);
        $this->andWhere('vou_datetime >= :start AND vou_datetime <= :finish', [
            ':start' => strtotime($range[0]),
            ':finish' => strtotime($range[1])
        ]);
        return $this;
    }

    public function getTotalVouchers($date)
    {
        return $this->getVouchers($date)->count();
    }

    public function getTopVouchers($date)
    {
        $this->select('vou_reward_name, SUM(vou_id) AS unit, SUM(vou_value) AS value, SUM(vou_original_price) AS price');
        if(Yii::$app->user->identity->type == 3) {
            $this->leftJoin('tbl_mall_merchant b', 'b.mam_com_id = vou_com_id');
            $this->andWhere('b.mam_mal_id = :mal_id', [':mal_id' => Yii::$app->user->identity->mall]);
        }
        $range = explode(" - ", $date);
        $this->andWhere('vou_datetime >= :start AND vou_datetime <= :finish', [
            ':start' => strtotime($range[0]),
            ':finish' => strtotime($range[1])
        ]);
        $this->groupBy('vou_reward_name');
        $this->orderBy('SUM(vou_value) DESC');
        return $this;
    }

}
