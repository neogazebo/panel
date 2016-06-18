<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SnapearnPoint]].
 *
 * @see SnapearnPoint
 */
class SnapearnPointQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SnapearnPoint[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SnapearnPoint|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
