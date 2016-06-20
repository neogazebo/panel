<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[WorkingTime]].
 *
 * @see WorkingTime
 */
use Yii;

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

    public function findWorkExist($param)
    {
        $user = Yii::$app->user->id;
        $this->andWhere("wrk_by = $user");
        $this->andWhere("wrk_param_id = $param ");
        $this->andWhere("wrk_end IS NULL");
        return $this;
    }

    public function getWorker()
    {
        $this->select('wrk_id,wrk_by,wrk_param_id,sum(wrk_time) as total_record');
        $this->where('wrk_end IS NOT NULL');
        if (!empty($_POST['wrk_by'])) {
            $this->andWhere('wrk_by = :id',[
                    ':id' => $_POST['wrk_by']
                ]);
        }
        $this->andWhere('wrk_time IS NOT NULL');
        $this->groupBy('wrk_by');
        return $this;
    }
}
