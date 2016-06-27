<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SnapearnPointDetail]].
 *
 * @see SnapearnPointDetail
 */
class SnapearnPointDetailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SnapearnPointDetail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SnapearnPointDetail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
