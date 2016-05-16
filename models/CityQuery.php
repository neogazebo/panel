<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class CityQuery extends ActiveQuery
{
    public function getList()
    {
    	$this->andWhere('cit_status = :status', [':status' => 1]);
        if(isset($_GET['search'])) {
            $this->andWhere('cit_name LIKE :get', [':get' => '%' . $_GET['search'] . '%']);
        }
    	return $this;
    }

}
