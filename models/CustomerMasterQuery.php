<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CustomerMaster]].
 *
 * @see CustomerMaster
 */
class CustomerMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CustomerMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CustomerMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getAreCustomer($cus_id,$com_id)
    {
        $this->andWhere('cus_mem_id = :mem',[
                ':mem' => $cus_id
            ]);
        $this->andWhere('cus_com_id = :com',[
                ':com' => $com_id
            ]);
        return $this->one();
    }
}
