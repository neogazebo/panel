<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class VoucherBoughtDetailQuery extends ActiveQuery {

    public function getList($id)
    {
        $this->leftJoin('tbl_voucher_bought', 'vod_vob_id = vob_id');
        $this->leftJoin('tbl_voucher', 'vou_id = vob_vou_id');
        if(isset($_GET['search'])) {
            $this->where('vod_sn LIKE :get OR vod_code LIKE :get', [
                ':get' => '%' . $_GET['search'] . '%'
            ]);
        }
        $this->andWhere(['vob_vou_id' => $id]);
        $this->orderBy('vod_id DESC');
        return $this;
    }

}
