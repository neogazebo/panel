<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SystemMessage]].
 *
 * @see SystemMessage
 */
class SystemMessageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SystemMessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SystemMessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
