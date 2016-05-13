<?php

namespace app\models;

use Yii;
use app\models\AuthRule;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;

class AuthItemQuery extends ActiveQuery
{
    const TYPE_ROLE = 1;
    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 0;
    
	public function getList()
	{
		$this->where('type = :type AND status = :status', [
			':type' => self::TYPE_ROLE,
			':status' => self::ACTIVE_STATUS
		]);
		$this->orderBy('from_unixtime(created_at) DESC');
		return $this;
	}

	public function getListPermission($name)
	{
		$this->andWhere('name != :name',[
				':name' => $name
			]);
		// $this->andWhere("substr(name, -1) != '/'");
		// $this->andWhere("LEFT(name, 1) = '/'");
		$this->orderBy('date(from_unixtime(created_at)) DESC');
		return $this;
	}
}