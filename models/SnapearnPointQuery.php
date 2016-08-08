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
 
    public function getReport($id,$date)
    {
        $this->select([
            'spo_name',
            'spo_point',
            'COUNT(IF(wrk_rjct_number = spo_id, wrk_id,0)) as activity',
            'SUM(IF(wrk_point = spo_point, spo_point,0)) as total_point',
            'SUM(wrk_time) as total_time',
            ]);
        $this->leftJoin('tbl_working_time', 'tbl_working_time.wrk_rjct_number = spo_id');
        $this->where('wrk_by = :id',[
            ':id' => $id
        ]);
        $this->andWhere('wrk_time IS NOT NULL');
        $this->andWhere('wrk_updated IS NOT NULL');
        $this->groupBy('spo_id');
//        var_dump($this->all());exit;
        return $this;
    }
}
