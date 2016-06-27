<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class MobilePulsaTopupQuery extends ActiveQuery
{
	public function getList()
	{
		if(isset($_GET['search'])) {
			$this->leftJoin('tbl_redemption_partner b', 'b.red_id = mpt_red_id');
			$this->andWhere('
				mpt_product_code LIKE :get OR 
				mpt_msisdn LIKE :get OR 
				mpt_ref_id LIKE :get OR 
				mpt_message LIKE :get OR 
				b.red_name LIKE :get
			', [':get' => '%' . $_GET['search'] . '%']);
		}
                $this->orderBy('mpt_datetime DESC');
		return $this;
	}

}
