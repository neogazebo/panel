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
}
