<?php

namespace app\models;

use yii\db\Expression;
use app\components\helpers\DateRangeCarbon;

/**
 * This is the ActiveQuery class for [[AccountDevice]].
 *
 * @see AccountDevice
 */
class SnapEarnQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AccountDevice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AccountDevice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function findCustome()
    {
        $timezone = date_default_timezone_get();
        $ch = curl_init('ipinfo.io/country');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $country = curl_exec($ch);

        $this->leftJoin('tbl_account', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        if(!empty($_GET['sna_cty'])) {
            $sna_cty = $_GET['sna_cty'];
            $this->andWhere(['LIKE', 'tbl_account.acc_country', $sna_cty]);
        }

        if(!empty($_GET['sna_member'])) {
            $sna_member = $_GET['sna_member'];
            $this->andWhere(['LIKE', 'tbl_account.acc_screen_name', $sna_member]);
        }
        if (!empty($_GET['sna_status'])) {
            $sna_status = $_GET['sna_status'];
            switch ($_GET['sna_status']) {
                case 'NEW':
                    $sna_status = 0;
                    break;
                case 'APP':
                    $sna_status = 1;
                    break;
                case 'REJ':
                    $sna_status = 2;
                    break;
            }
            $this->andWhere(['like','sna_status',$sna_status]);
        }
        if (!empty($_GET['sna_daterange'])) {
            $sna_daterange = explode(' to ', ($_GET['sna_daterange']));
            $timezone = $country == 'MY' ? 8 : 7;
            $this->andWhere("FROM_UNIXTIME(sna_upload_date) BETWEEN '$sna_daterange[0] 00:00:00' AND '$sna_daterange[1] 23:59:59'");
        }
        if (!empty($_GET['sna_join'])) {
            $sna_join = $_GET['sna_join'];
            $this->leftJoin('tbl_company', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        }
        $this->orderBy('sna_id DESC');
        // echo $this->createCommand()->sql;exit;
        return $this;
    }

    public function getLastUpload($id)
    {
        $this->andWhere('sna_acc_id = :id', [
            ':id' => $id
        ]);
        $this->orderBy('sna_id DESC');
        $this->limit(1);
        return $this;
    }

    public function saveNext($id, $ctr)
    {
        $this->leftJoin('tbl_account', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('sna_id < :id', [':id' => $id]);
        $this->andWhere('acc_country = :ctr', [
            ':ctr' => $ctr
        ]);
        $this->andWhere('sna_status = 0');
        $this->orderBy('sna_id DESC');
        $this->limit(1);
        return $this->one();
    }

    public function maxDuplicateReceipt($t, $u, $c)
    {
        $this->andWhere('
            DATE(FROM_UNIXTIME(sna_transaction_time)) = :time
            AND sna_acc_id = :acc
            AND sna_com_id = :com
            AND sna_transaction_time != 0
        ', [
            ':time' => date('Y-m-d', strtotime($t)),
            ':acc' => $u,
            ':com' => $c
        ]);
        $this->all();
        return $this;
    }

    public function setChartTopFour($filters = null)
    {
        $dt = new DateRangeCarbon();
        $userId = $_POST['id'];

        if ($filters != null) {
            switch ($filters) {
    			case 'thisMonth':
    				$dt = $dt->getThisMonth();
    				break;
    			case 'lastMonth':
    				$dt = $dt->getLastMonth();
    				break;
                case 'thisWeek':
                    $dt = $dt->getThisWeek();
                    break;
                case 'lastWeek':
                    $dt = $dt->getLastWeek();
                    break;
    		}
            $sna_daterange = explode(' to ', ($dt));
        } else
            $sna_daterange = explode(' to ', ($dt->getThisMonth()));

        $this->select("
            cat_name as categoryName,
            sum(sna_receipt_amount) as amount,
            acc_country as country
        ");
        $this->innerJoin('tbl_category','tbl_category.cat_id = tbl_snapearn.sna_cat_id');
        $this->leftJoin("tbl_account","tbl_account.acc_id = tbl_snapearn.sna_acc_id");
        $this->where('
            sna_acc_id = :id
            AND sna_status = 1

        ', [
            ':id' => $userId
        ]);
        $this->andWhere("FROM_UNIXTIME(sna_upload_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->groupBy('sna_cat_id');
        $this->orderBy('sum(sna_receipt_amount) DESC');
        $this->limit(4);
        return $this->all();
    }

    public function uploadIdChart($filters = null)
    {
        $dt = new DateRangeCarbon();
        $this->select('date(from_unixtime(sna_upload_date)) as tanggal, count(*) as jumlah');
        if ($filters != null) {
            switch ($filters) {
                case 'thisMonth':
                    $dt = $dt->getThisMonth();
                    break;
                case 'lastMonth':
                    $dt = $dt->getLastMonth();
                    break;
                case 'thisWeek':
                    $dt = $dt->getThisWeek();
                    break;
                case 'lastWeek':
                    $dt = $dt->getLastWeek();
                    break;
            }
            $sna_daterange = explode(' to ', ($dt));
        } else
            $sna_daterange = explode(' to ', ($dt->getThisMonth()));

        $this->andWhere("FROM_UNIXTIME(sna_upload_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('tbl_account.acc_cty_id = :cty',[
            ':cty' => 'ID'
        ]);
        $this->groupBy('date(from_unixtime(sna_upload_date)) ');
        return $this;
    }

    public function uploadMyChart($filters = null)
    {
        $dt = new DateRangeCarbon();
        $this->select('date(from_unixtime(sna_upload_date)) as tanggal, count(*) as jumlah');
        if ($filters != null) {
            switch ($filters) {
                case 'thisMonth':
                    $dt = $dt->getThisMonth();
                    break;
                case 'lastMonth':
                    $dt = $dt->getLastMonth();
                    break;
                case 'thisWeek':
                    $dt = $dt->getThisWeek();
                    break;
                case 'lastWeek':
                    $dt = $dt->getLastWeek();
                    break;
            }
            $sna_daterange = explode(' to ', ($dt));
        } else
            $sna_daterange = explode(' to ', ($dt->getThisMonth()));

        $this->andWhere("FROM_UNIXTIME(sna_upload_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('tbl_account.acc_cty_id = :cty',[
            ':cty' => 'MY'
        ]);
        $this->groupBy('date(from_unixtime(sna_upload_date)) ');
        return $this;
    }

    public function approveIdChart($filters = '')
    {
        $dt = new DateRangeCarbon();
        $this->select('date(from_unixtime(sna_review_date)) as tanggal, count(*) as jumlah');
        if ($filters != null) {
            switch ($filters) {
                case 'thisMonth':
                    $dt = $dt->getThisMonth();
                    break;
                case 'lastMonth':
                    $dt = $dt->getLastMonth();
                    break;
                case 'thisWeek':
                    $dt = $dt->getThisWeek();
                    break;
                case 'lastWeek':
                    $dt = $dt->getLastWeek();
                    break;
            }
            $sna_daterange = explode(' to ', ($dt));
        } else
            $sna_daterange = explode(' to ', ($dt->getThisMonth()));

        $this->andWhere("FROM_UNIXTIME(sna_review_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('sna_status = :status',[
            ':status' => SnapEarn::STATUS_APPROVED
        ]);
        $this->andWhere('tbl_account.acc_cty_id = :cty',[
            ':cty' => 'ID'
        ]);
        $this->groupBy('date(from_unixtime(sna_review_date)) ');
        return $this;
    }

    public function approveMyChart($filters = '')
    {
        $dt = new DateRangeCarbon();
        $this->select('date(from_unixtime(sna_review_date)) as tanggal, count(*) as jumlah');
        if ($filters != null) {
            switch ($filters) {
                case 'thisMonth':
                    $dt = $dt->getThisMonth();
                    break;
                case 'lastMonth':
                    $dt = $dt->getLastMonth();
                    break;
                case 'thisWeek':
                    $dt = $dt->getThisWeek();
                    break;
                case 'lastWeek':
                    $dt = $dt->getLastWeek();
                    break;
            }
            $sna_daterange = explode(' to ', ($dt));
        } else
            $sna_daterange = explode(' to ', ($dt->getThisMonth()));

        $this->andWhere("FROM_UNIXTIME(sna_review_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('sna_status = :status',[
            ':status' => SnapEarn::STATUS_APPROVED
        ]);
        $this->andWhere('tbl_account.acc_cty_id = :cty',[
            ':cty' => 'MY'
        ]);
        $this->groupBy('date(from_unixtime(sna_review_date)) ');
        return $this;
    }

    public function rejectIdChart($filters = '')
    {
        $dt = new DateRangeCarbon();
        $this->select('date(from_unixtime(sna_review_date)) as tanggal, count(*) as jumlah');
        if ($filters != null) {
            switch ($filters) {
                case 'thisMonth':
                    $dt = $dt->getThisMonth();
                    break;
                case 'lastMonth':
                    $dt = $dt->getLastMonth();
                    break;
                case 'thisWeek':
                    $dt = $dt->getThisWeek();
                    break;
                case 'lastWeek':
                    $dt = $dt->getLastWeek();
                    break;
            }
            $sna_daterange = explode(' to ', ($dt));
        } else
            $sna_daterange = explode(' to ', ($dt->getThisMonth()));

        $this->andWhere("FROM_UNIXTIME(sna_review_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('sna_status = :status',[
            ':status' => SnapEarn::STATUS_REJECTED
        ]);
        $this->andWhere('tbl_account.acc_cty_id = :cty',[
            ':cty' => 'ID'
        ]);
        $this->groupBy('date(from_unixtime(sna_review_date)) ');
        return $this;
    }

    public function rejectMyChart($filters = '')
    {
        $dt = new DateRangeCarbon();
        $this->select('date(from_unixtime(sna_review_date)) as tanggal, count(*) as jumlah');
        if ($filters != null) {
            switch ($filters) {
                case 'thisMonth':
                    $dt = $dt->getThisMonth();
                    break;
                case 'lastMonth':
                    $dt = $dt->getLastMonth();
                    break;
                case 'thisWeek':
                    $dt = $dt->getThisWeek();
                    break;
                case 'lastWeek':
                    $dt = $dt->getLastWeek();
                    break;
            }
            $sna_daterange = explode(' to ', ($dt));
        } else
            $sna_daterange = explode(' to ', ($dt->getThisMonth()));

        $this->andWhere("FROM_UNIXTIME(sna_review_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('sna_status = :status',[
            ':status' => SnapEarn::STATUS_REJECTED
        ]);
        $this->andWhere('tbl_account.acc_cty_id = :cty',[
            ':cty' => 'MY'
        ]);
        $this->groupBy('date(from_unixtime(sna_review_date)) ');
        return $this;
    }

    public function getUniqueUser()
    {
        $this->select(new Expression('yearweek(from_unixtime(sna_upload_date),3) as weeks, count(distinct sna_acc_id) as total_unique, count(sna_acc_id) as total_user'));
        $this->groupBy(new Expression('yearweek(from_unixtime(sna_upload_date),3) DESC'));
        return $this;
    }
}
