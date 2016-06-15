<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CompanyTag]].
 *
 * @see CompanyTag
 */
class CompanyTagQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CompanyTag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CompanyTag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
