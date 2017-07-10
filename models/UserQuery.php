<?php

namespace app\models;

use Yii;
use app\models\AuthAssignment;
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
        $role = $_GET['role'];
        $this->select('id, username');
        if(!empty($role)) {
            $exists = AuthAssignment::find()->where('item_name = :role',[':role'=>$role])->all();
            foreach ($exists as $value) {
                $this->andWhere(['<>','id',$value->user_id]);
            }
        }
        $this->andWhere('username LIKE "%'.$search.'%" ');
        return $this->all();
    }

    public function findUser()
    {
        $search = $_GET['q'];
        $this->select('id, username');
        $this->andWhere('username LIKE "%'.$search.'%" ');
        return $this->all();
    }

    public function getRoles()
    {
        $user = Yii::$app->user->getIdentity(); 
        return $user->roles;
    }

    public function getRoleNames()
    {
        $role_names = [];
        $roles = $this->getRoles();
        
        if($roles)
        {
            foreach ($roles as $role) 
            {
                array_push($role_names, $role->item_name);
            }
        }

        return $role_names;
    }
}
