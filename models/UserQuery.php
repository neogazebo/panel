<?php

namespace app\models;

use Yii;
/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getCron($date)
    {
        echo $date;
    }

    public function searchUser()
    {
        $search = $_GET['q'];
        $this->select('id, username');
        $this->andWhere('username LIKE "%'.$search.'%" ');
        return $this->all();
    }
}