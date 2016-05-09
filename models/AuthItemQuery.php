<?php
namespace app\models;


use Yii;
use app\models\AuthRule;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
/**
* 
*/
class AuthItemQuery extends ActiveQuery
{

    
    const TYPE_ROLE = 1;
    const ACTIVE_STATUS = 1;
    const INACTIVE_STATUS = 0;
    
	public function getListRole()
	{
		$this->andWhere('type = :type',[
				':type' => self::TYPE_ROLE
			]);
		$this->andWhere('status = :status',[
				':status' => self::ACTIVE_STATUS,
			]);
		$this->orderBy('date(from_unixtime(created_at)) DESC');
		return $this;
	}
}