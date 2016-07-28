<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LoyaltyPointHistory]].
 *
 * @see LoyaltyPointHistory
 */
class LoyaltyPointHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return LoyaltyPointHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LoyaltyPointHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getTotalPointMember($id)
    {
        $this->andWhere('lph_acc_id = :id',[
            ':id'=>$id
        ]);
        $this->orderBy('lph_id DESC');
        $this->limit(1);
        return $this->one();
    }
    
    public function getCurrentPoint($id)
    {
        $this->andWhere('lph_acc_id = :acc_id',[
            ':acc_id'=>$id
        ]);
        $this->orderBy('lph_id DESC');
        return $this->one();
    }
}
