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
        $date = explode(' to ', $date);
        $first_date = $date[0] . ' 00:00:00';
        $last_date = $date[1] . ' 23:59:59';
        $first = WorkingTime::find()
                ->andWhere('FROM_UNIXTIME(wrk_updated) >= :first AND wrk_time IS NOT NULL',[
                    ':first' =>  $first_date,
                ])->limit(1)
                ->one()->wrk_id;
        
        $last = WorkingTime::find()
                ->andWhere('FROM_UNIXTIME(wrk_updated) <= :last AND wrk_time IS NOT NULL',[
                    ':last' =>  $last_date,
                ])->limit(1)
                ->orderBy('wrk_id DESC')
                ->one()->wrk_id;
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
        $this->andWhere('wrk_id BETWEEN :first AND :last',[
            ':first' => $first,
            ':last' => $last
        ]);
        $this->andWhere('wrk_time IS NOT NULL');
        $this->andWhere('wrk_updated IS NOT NULL');
        $this->groupBy('spo_id');
        return $this;
    }
}
