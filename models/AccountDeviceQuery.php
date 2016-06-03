<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AccountDevice]].
 *
 * @see AccountDevice
 */
class AccountDeviceQuery extends \yii\db\ActiveQuery
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

    public function getLastLocatione($id)
    {
        $this->andWhere('adv_acc_id = :id',[
                ':id' => $id
            ]);
        $this->orderBy('date(from_unixtime(adv_last_access)) DESC');
        $this->limit(5);
        return $this;
    }
}
