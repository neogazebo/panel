<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MallCategory]].
 *
 * @see MallCategory
 */
class MallCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MallCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MallCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
