<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LoyaltyPointType]].
 *
 * @see LoyaltyPointType
 */
class LoyaltyPointTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return LoyaltyPointType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LoyaltyPointType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
