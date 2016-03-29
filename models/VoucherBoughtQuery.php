<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class VoucherBoughtQuery extends ActiveQuery
{
    public function getList()
    {
        $this->leftJoin('tbl_voucher', 'vou_id = vob_vou_id');
        if(isset($_GET['search'])) {
            $this->andWhere('
                vou_reward_name LIKE :get OR 
                vou_description LIKE :get OR 
                vob_qty LIKE :get OR 
                vob_price LIKE :get 
            ', [':get' => '%' . $_GET['search'] . '%']);
        }
        $this->orderBy('vou_valid_end DESC');
        return $this;
    }

    public function getListDetail($id)
    {
        $this->leftJoin('tbl_voucher', 'vou_id = vob_vou_id');
        if(isset($_GET['search'])) {
            $this->andWhere('
                vou_reward_name LIKE :get AND 
                vou_description LIKE :get AND 
                vob_qty LIKE :get OR 
                vob_price LIKE :get 
            ', [':get' => '%' . $_GET['search'] . '%']);
        }
        $this->andWhere('vob_id = :vob_id', [':vob_id' => $id]);
        $this->orderBy('vou_valid_end DESC');
        return $this;
    }

}
