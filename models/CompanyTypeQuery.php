<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CompanyType]].
 *
 * @see CompanyType
 */
class CompanyTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CompanyType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CompanyType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
