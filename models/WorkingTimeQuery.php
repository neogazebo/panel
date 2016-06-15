<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[WorkingTime]].
 *
 * @see WorkingTime
 */
class WorkingTimeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return WorkingTime[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return WorkingTime|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function findWorkExist($user,$param)
    {
        $this->andWhere("wrk_by = $user");
        $this->andWhere("wrk_param_id = $param ");
        return $this->all();
    }
}
