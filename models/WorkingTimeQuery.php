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

    public function findWorkExist($param, $point_type = 0)
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
        $this->select("
            wrk_id,
            wrk_type,
            wrk_by,
            wrk_param_id,
            SUM(wrk_time) AS total_record,
            SUM(wrk_point) AS total_point,
            SUM(wrk_type = 1) AS total_approved,
            SUM(wrk_type = 2) AS total_rejected,
            COUNT(IF(wrk_type = 2, wrk_id, null)) / COUNT(wrk_id) AS rejected_rate
        ");
        $this->where('wrk_end IS NOT NULL');
        if (!empty($_POST['wrk_by'])) {
            $this->andWhere('wrk_by = :id', [
                ':id' => $_POST['wrk_by']
            ]);
        }
        if (!empty($_POST['wrk_daterange'])) {
            $range = explode(" to ", $_POST['wrk_daterange']);
            $this->andWhere("DATE(FROM_UNIXTIME(wrk_updated)) BETWEEN '$range[0]' AND '$range[1]'");
        }

        $this->andWhere('wrk_time IS NOT NULL');
        $this->groupBy('wrk_by');
        return $this;
    }

    public function detailPoint($id)
    {
        $this->where('wrk_by = :user AND wrk_end IS NOT NULL', [
            ':user' => $id
        ]);

        if (!empty($_POST['wrk_daterange'])) {
            $range = explode(" to ", $_POST['wrk_daterange']);
            $this->andWhere("date(FROM_UNIXTIME(wrk_updated)) BETWEEN '$range[0]' AND '$range[1]'");
        }

        $this->andWhere('wrk_time IS NOT NULL');
        $this->orderBy('wrk_id DESC');
        return $this;
    }

    public function getReport($id, $date)
    {
        $date = explode(' to ', $date);
        $first_date = $date[0] . ' 00:00:00';
        $last_date = $date[1] . ' 23:59:59';

        $this->where('
            wrk_by = :user 
            AND wrk_end IS NOT NULL 
            AND DATE(FROM_UNIXTIME(wrk_updated)) BETWEEN :first_date AND :last_date 
            AND wrk_time IS NOT NULL
        ', [
            ':user' => $id,
            ':first_date' => $first_date,
            ':last_date' => $last_date,
        ]);
        $this->orderBy('wrk_id DESC');
        return $this;
    }

}