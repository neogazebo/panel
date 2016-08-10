<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[LoyaltyPointHistory]].
 *
 * @see LoyaltyPointHistory
 */
class LoyaltyPointHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return LoyaltyPointHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LoyaltyPointHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getTotalPointMember($id)
    {
        $this->andWhere('lph_acc_id = :id',[
            ':id'=>$id
        ]);
        $this->orderBy('lph_id DESC');
        $this->limit(1);
        return $this->one();
    }
    
    public function getCurrentPoint($id)
    {
//        echo $id;exit;
        $db2 = Yii::$app->db2;
        $rows = $db2->useMaster(function($db) use ($id){
            return $db->createCommand("SELECT lph_total_point FROM tbl_loyalty_point_history WHERE lph_acc_id=:id ORDER BY lph_id DESC limit 1")
                    ->bindValue(':id',$id)
                    ->queryOne();
        });
        return $rows;
        
//        $rest = Yii::$app->db2
//                ->useMaster
//                ->createCommand("SELECT lph_total_point FROM tbl_loyalty_point_history WHERE lph_acc_id = :id ORDER BY lph_id DESC LIMIT 1")
//                ->queryOne();
//        return $rest;
//        $rows = \Yii::$app->db2->useMaster(function ($db) {
//            return $db->createCommand('SELECT lph_total_point'
//                    . 'FROM tbl_loyalty_point_history'
//                    . "WHERE lph_acc_id = $id"
//                    . 'ORDER BY lph_id DESC'
//                    . 'LIMIT 1');
//        });
//        $this->andWhere('lph_acc_id = :acc_id',[
//            ':acc_id'=>$id
//        ]);
//        $this->orderBy('lph_id DESC');
//        $this->limit(1);
//        return $this->one();
    }

    public function getMemberPointHistory($member_id)
    {
        $query = (new Query)
            ->select([
                'com_name AS merchant',
                'lpe_name AS type',
                'lph_amount AS point',
                'IF(lph_type = "C", "Credit", "Debit") AS method',
                'FROM_UNIXTIME(lph_datetime) AS created_date',
                'lph_total_point AS total_point',
                'lph_current_point as current_point',
                'lph_approve AS status'
            ])
            ->from('tbl_loyalty_point_history')
            ->innerJoin('tbl_member', 'mem_id = lph_mem_id')
            ->leftJoin('tbl_company', 'com_id = lph_com_id')
            ->leftJoin('tbl_loyalty_point_type', 'lpe_id = lph_lpe_id')
            ->where('lph_mem_id = :id', [':id' => $member_id])
            ->orderBy('lph_datetime DESC');

        return $query;
    }

    public function filterMemberPointHistory($member_id, $date_range, $op_type, $offset, $limit)
    {
        if($date_range)
        {
            $date_range = explode(' ', $date_range);
            $start_date = strtotime($date_range[0]);
            $end_date = strtotime($date_range[2]);
        }

        $results = new Query();
        
        $results->select([
                'com_name AS merchant',
                'lpe_name AS type',
                'lph_amount AS point',
                'IF(lph_type = "C", "Credit", "Debit") AS method',
                'FROM_UNIXTIME(lph_datetime) AS created_date',
                'lph_total_point AS total_point',
                'lph_current_point as current_point',
                'lph_approve AS status'
            ])
            ->from('tbl_loyalty_point_history')
            ->innerJoin('tbl_member', 'mem_id = lph_mem_id')
            ->leftJoin('tbl_company', 'com_id = lph_com_id')
            ->leftJoin('tbl_loyalty_point_type', 'lpe_id = lph_lpe_id');

        if($op_type == 'filter')
        {
            $results->where('
                lph_mem_id = :id 
                AND
                lph_datetime >= :start_date
                AND
                lph_datetime <= :end_date
            ', [
                ':id' => $member_id,
                ':start_date' => $start_date,
                ':end_date' => $end_date
            ]);
        }
        else
        {
            $results->where('lph_mem_id = :id', [
                ':id' => $member_id
            ]);
        }
        
        if($offset && $limit)
        {
            $results->limit($limit)->offset($offset);
        }
        
        $results->orderBy('lph_datetime DESC');

        return $results;
    }
}
