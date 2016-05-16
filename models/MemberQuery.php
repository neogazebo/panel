<?php

namespace common\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveQuery;

class MemberQuery extends ActiveQuery
{

    public function getMyMember()
    {
//        $this->innerJoinWith('customer');
        $this->joinWith('customer', true, 'INNER JOIN');
        $this->where('cus_com_id = :com_id', [':com_id' => Yii::$app->loggedin->company->com_id]);

//        echo $this->createCommand()->sql;
//        echo Yii::$app->loggedin->company->com_id;
//        exit;
        return $this;
    }

    public function range($date)
    {
        $range = explode(" - ", $date);
        if (count($range) == 2)
        {
            $this->andWhere('poh_close_datetime > :start and poh_close_datetime < :finish', [
                ':start' => strtotime($range[0]),
                ':finish' => strtotime($range[1])
            ]);
        }
        return $this;
    }

    public function analytic($date)
    {
        $range = explode(" - ", $date);
        if (count($range) == 2)
        {
            $this->select('COUNT(*) AS `total`, DATE_ADD( DATE(mem_datetime), INTERVAL (7 - DAYOFWEEK( mem_datetime )) DAY) `week`');
            $this->andWhere('mem_datetime > :start and mem_datetime < :finish', [
                ':start' => strtotime($range[0]),
                ':finish' => strtotime($range[1])
            ]);
            $this->groupBy('`week`');
            $this->orderBy('mem_datetime');
        }
        return $this;
    }

    public function getTopmember()
    {
        if (Yii::$app->user->identity->type == 3)
        {
            $this->leftJoin('tbl_company b', 'b.com_usr_id = mem_usr_id');
            $this->andWhere(['LIKE', 'com_name', '@ BSC']);
        }
        return $this;
    }

    public function getList($date = null)
    {
        if(is_null($date))
        {
            $date = date('d-m-Y 00:00:00 - d-m-Y 23:59:59');
        }
        $range = explode(" - ", $date);
        $this->innerJoin('tbl_user', 'usr_id = mem_usr_id');
        if (isset($_GET['search'])) {
            $this->andWhere('
                mem_screen_name LIKE :get OR
                mem_first_name LIKE :get OR
                mem_last_name LIKE :get OR
                mem_email LIKE :get OR
                mem_ref_code LIKE :get
            ', [':get' => '%' . $_GET['search'] . '%']);
        }
        if(isset($_GET['manis']) && $_GET['manis'] != 'ALL') {
            if($_GET['manis'] == '1')
                $this->andWhere('usr_mal_id IS NULL');
            elseif($_GET['manis'] == '2')
                $this->andWhere('usr_mal_id IS NOT NULL');
        }
        if (isset($_GET['country'])) {
            if ($_GET['country'] == 'MY')
                $this->andWhere('mem_register_country = :cny ', [':cny' => 'MY']);
            elseif ($_GET['country'] == 'ID')
                $this->andWhere('mem_register_country = :cny ', [':cny' => 'ID']);
        }
        if (Yii::$app->user->identity->type == 3) {
            $this->andWhere(['usr_mal_id' => Yii::$app->user->identity->mall]);
        }
        $this->andWhere('usr_createdate >= :first AND usr_createdate <= :finish', [
            ':first' => strtotime($range[0]),
            ':finish' => strtotime($range[1])
        ]);
        return $this;
    }

    public function getMemberCount()
    {
        return $this->getMember()->count();
    }

    public function getNewMember()
    {

    }

    public function getThisMonth()
    {
        $this->where("FROM_UNIXTIME(mem_datetime, '%Y') = YEAR(NOW()) AND FROM_UNIXTIME(mem_datetime, '%m') = MONTH(NOW()) ");
        return $this;
    }

    public function getLastMonth()
    {
        $this->where("FROM_UNIXTIME(mem_datetime, '%Y') = YEAR(NOW()) AND FROM_UNIXTIME(mem_datetime, '%m') = MONTH(NOW()) - 1 ");
        return $this;
    }

    public function countCustomer($date)
    {
        $this->innerJoin('tbl_company b', 'b.com_usr_id = mem_usr_id');
        if (Yii::$app->user->identity->type == 3)
        {
            $this->leftJoin('tbl_mall_merchant c', 'c.mam_com_id = b.com_id');
            $this->andWhere(['c.mam_mal_id' => Yii::$app->user->identity->mall]);
        }

        return $this->count();
    }

    public function countCustomerMall($date)
    {
        // $this->leftJoin('tbl_company b', 'b.com_usr_id = mem_usr_id');
        // if(Yii::$app->user->identity->type == 3) {
        //     $this->leftJoin('tbl_mall_merchant c', 'c.mam_com_id = b.com_id');
        //     $this->andWhere(['c.mam_mal_id' => Yii::$app->user->identity->mall]);
        // }
        $range = explode(" - ", $date);
        $this->join('INNER JOIN', 'tbl_user b', 'b.usr_id = mem_usr_id');
        $this->andWhere(['mem_account_status' => 1]);
        if (Yii::$app->user->identity->type == 3)
        {
            $this->andWhere('b.usr_mal_id = :mall and (b.usr_createdate >= :start and b.usr_createdate <= :finish)', [
                ':mall' => Yii::$app->user->identity->mall,
                ':start' => strtotime($range[0]),
                ':finish' => strtotime($range[1])
            ]);
        }
        else
        {
            $this->andWhere('b.usr_createdate >= :start and b.usr_createdate <= :finish', [
                ':start' => strtotime($range[0]),
                ':finish' => strtotime($range[1])
            ]);
        }
        return $this->count();
    }

    protected function identity()
    {
        $this->leftJoin('tbl_company b', 'b.com_usr_id = mem_usr_id');
        if (Yii::$app->user->identity->type == 3)
        {
            $this->leftJoin('tbl_mall_merchant c', 'c.mam_com_id = b.com_id');
            $this->andWhere(['c.mam_mal_id' => Yii::$app->user->identity->mall]);
        }
        return $this;
    }

    protected function getPercentageStart($where = [])
    {
        $this->andWhere([
            'FROM_UNIXTIME(`com_created_date`, "%Y")' => 'YEAR(NOW())',
            'FROM_UNIXTIME(`com_created_date`, "%m")' => 'YEAR(NOW())'
        ])->identity();
        if (count($where) > 0)
            $this->andWhere($where);
        return $this->count();
    }

    protected function getPercentageEnd($where = [])
    {
        $this->andWhere([
            'FROM_UNIXTIME(`com_created_date`, "%Y")' => 'YEAR(NOW())',
            'FROM_UNIXTIME(`com_created_date`, "%m")' => 'YEAR(NOW()) - 1'
        ]);
        if (count($where) > 0)
            $this->andWhere($where);
        return $this->count();
    }

    public function percentageMember()
    {
        // $this->join('INNER JOIN', 'tbl_user b', 'b.usr_id = mem_usr_id');
        return $percentageMember = $this->getPercentageStart() - $this->getPercentageEnd() / 100;
    }

    public function returnCustomer()
    {
        $this->leftJoin('tbl_tracker d', 'd.tra_mem_id = mem_id');
        return $this;
    }

    public function getNewThisWeek()
    {
        $this->andWhere('WEEKOFYEAR(from_unixtime(mem_datetime))=WEEKOFYEAR(NOW()) AND YEAR(from_unixtime(mem_datetime))=YEAR(NOW())');
        return $this;
    }

    public function getActiveMember()
    {
        $this->andWhere(['mem_account_status' => Member::MEMBER_ACCOUNT_ACTIVE]);
        return $this;
    }
    public function getInactiveMember()
    {
        $this->andWhere(['mem_account_status' => Member::MEMBER_ACCOUNT_INACTIVE]);
        return $this;
    }

    public function getCurrentActiveNewUser($day){
        $this->innerJoin('tbl_user','tbl_user.usr_id = tbl_member.mem_usr_id');
        $this->andwhere('usr_mal_id is :null AND date(from_unixtime(mem_datetime)) = :dat AND mem_register_country = :id',[
          ':null'=>null ,
          ':dat'=>$day,
          ':id'=> 'ID'
        ]);
        return $this->count();
    }

    public function getNumberOfReferral($day){
        $this->innerJoin('tbl_user','tbl_user.usr_id = tbl_member.mem_usr_id');
        $this->andwhere('usr_mal_id is :null AND date(from_unixtime(mem_datetime)) = :dat AND mem_register_country = :id AND (mem_ref_code IS NOT NULL OR TRIM(mem_ref_code) <> :empty)',[
          ':null'=> null ,
          ':dat'=> $day,
          ':empty'=> '',
          ':id'=> 'ID'
        ]);
        return $this->count();
    }

    public function getData($date = null)
    {
      if(!empty($_GET['country'])){
          $country = $_GET['country'];
      }else{
          $country = 'ID';
      }

      if(!empty($_GET['daterange'])){
          $date = $_GET['daterange'];
      }else{
          $date = date('Y-m-01').' to ' . date('Y-m-d');
      }

      $range = explode(" to ", $date);
      $range[1] = $range[1].' 23:59:59';
      $query = new yii\db\Query;
      $query->select(['mem_datetime','count(mem_id) AS total_active',"SUM(IF(mem_ref_code IS NOT NULL OR TRIM(mem_ref_code) != '',1,0)) AS number_referal"])
            ->from('tbl_member')
            ->innerJoin('tbl_user user','user.usr_id = tbl_member.mem_usr_id')
            ->where('user.usr_mal_id IS NULL AND mem_datetime >= :first AND mem_datetime <= :last AND mem_register_country = :country',[
                  ':first' => strtotime($range[0]),
                  ':last' => strtotime($range[1]),
                  ':country'=>$country
            ])
            ->groupBy('date(FROM_UNIXTIME(mem_datetime))');
      return $query;
    }

    public function getReturningMall($date)
    {
        $range = explode(" - ", $date);
        $addDay = strtotime('+1 day', strtotime($range[0]));
        $this->select('
            mem_id, count(*) as total
        ');
        $this->joinWith(['trackers','user']);
        $this->andWhere('mem_datetime > :date',[
                ':date' => $addDay
            ]);
        $this->andWhere('tbl_user.usr_mal_id = :mall_id',[
                ':mall_id' => Yii::$app->user->identity->mall
            ]);
        $this->andWhere('tbl_tracker.tra_response NOT LIKE "%500%"');
        $this->groupBy('mem_id');
        $this->having(['>=','count(*)', '2']);

        return $this;
    }
}
