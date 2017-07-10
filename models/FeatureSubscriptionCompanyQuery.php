<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[FeatureSubscriptionCompany]].
 *
 * @see FeatureSubscriptionCompany
 */
class FeatureSubscriptionCompanyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FeatureSubscriptionCompany[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FeatureSubscriptionCompany|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
