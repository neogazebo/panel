<?php

namespace app\models;

use Yii;

/**
 * This is the ActiveQuery class for [[SnapearnGroup]].
 *
 * @see SnapearnGroup
 */
class SnapearnGroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SnapearnGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SnapearnGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getListGroup()
    {
    	$this->select(['spg_id', 'spg_name'])
			->where('spg_name LIKE :name', [':name' => '%' . Yii::$app->request->get('q') . '%']);
		return $this->all();
    }

}
