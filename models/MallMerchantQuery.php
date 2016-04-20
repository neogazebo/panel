<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class MallMerchantQuery extends ActiveQuery {

    public function getList($id)
    {
        $this->leftJoin('tbl_company b', 'b.com_id = mam_com_id');
    	if(isset($_GET['search'])) {
    		$this->andWhere(['LIKE', 'mam_floor', $_GET['search']]);
            $this->orWhere(['LIKE', 'b.com_name', $_GET['search']]);
    		$this->orWhere(['LIKE', 'mam_unit_number', $_GET['search']]);
    	}
        $this->andWhere(['mam_mal_id' => $id]);
    	return $this;
    }

}
