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

    public function findWorkExist($param,$point_type = 0)
    {
        $user = Yii::$app->user->id;
        $this->andWhere("wrk_by = $user");
        $this->andWhere("wrk_param_id = $param");
        if ($point_type != 0) {
            $this->andWhere("wrk_point_type = $point_type");
        }
        $this->andWhere("wrk_end IS NULL");
        return $this;
    }

    public function getWorker()
    {
        $this->select("wrk_id,wrk_type,wrk_by,wrk_param_id,sum(wrk_time) as total_record,sum(wrk_point) as total_point,sum(wrk_type = 1) as total_approved, sum(wrk_type = 2) as total_rejected,  count(if(wrk_type = 2, wrk_id, null))/count(wrk_id) as rejected_rate");
        $this->where('wrk_end IS NOT NULL');
        if (!empty($_POST['wrk_by'])) {
            $this->andWhere('wrk_by = :id',[
                    ':id' => $_POST['wrk_by']
                ]);
        }
        if (!empty($_POST['wrk_daterange'])) {
            $range = explode(" to ", $_POST['wrk_daterange']);
            $this->andWhere("date(from_unixtime(wrk_updated)) BETWEEN '$range[0]' AND '$range[1]'");
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

        if (!empty($_POST['wrk_daterange'])) {
            $range = explode(" to ", $_POST['wrk_daterange']);
            $this->andWhere("date(from_unixtime(wrk_updated)) BETWEEN '$range[0]' AND '$range[1]'");
        }

        $this->andWhere('wrk_time IS NOT NULL');
        $this->orderBy('wrk_id DESC');
        return $this;
    }

}
