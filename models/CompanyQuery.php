<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AccountDevice]].
 *
 * @see AccountDevice
 */
class CompanyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AccountDevice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccountDevice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getCurrentPoint($com_id)
    {
        $this->andWhere('com_id = :com_id',[
            ':com_id'=>$com_id
        ]);
        return $this->one();
    }

    public function searchExistingMerchant()
    {
        $search = $_GET['q'];
        $keyword = preg_split("/[\s,]+/",$search);
        $this->select('com_id, com_name');
        foreach($keyword as $key){
            $this->andWhere('com_name LIKE "%'.$key.'%" ');
        }
        $this->andWhere('com_status != 2');
        return $this->all();
    }
}
