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
        $this->select('wrk_id,wrk_by,wrk_param_id,sum(wrk_time) as total_record,sum(wrk_point) as total_point');
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

    public function detailPoint($id)
    {
        $this->where('wrk_by = :user',[
                ':user' => $id
            ]);
        $this->andWhere('wrk_end IS NOT NULL');
       if (!empty($_GET['wrk_daterange'])){
            $wrk_daterange = explode(' to ',($_GET['wrk_daterange']));
            $this->andWhere("FROM_UNIXTIME(wrk_upload_date) BETWEEN '$wrk_daterange[0] 00:00:00' AND '$wrk_daterange[1] 23:59:59'");
        }
        $this->andWhere('wrk_time IS NOT NULL');
        return $this;
    }

}
