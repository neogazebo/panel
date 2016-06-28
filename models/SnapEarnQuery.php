<?php

namespace app\models;

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
        if(!empty($_GET['sna_cty'])){
            $sna_cty = $_GET['sna_cty'];
            $this->andWhere(['like','tbl_account.acc_cty_id',$sna_cty]);
        }

        if(!empty($_GET['sna_member'])){
            $sna_member = $_GET['sna_member'];
            $this->andWhere(['like','tbl_account.acc_screen_name',$sna_member]);
        }
        if (!empty($_GET['sna_status'])){
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
        if (!empty($_GET['sna_daterange'])){
            $sna_daterange = explode(' to ',($_GET['sna_daterange']));
                if($country == 'MY'){
                    $timezone = 8;
                } else {
                   $timezone = 7;
                }
            $this->andWhere("FROM_UNIXTIME(sna_upload_date) BETWEEN '$sna_daterange[0] 00:00:00' AND '$sna_daterange[1] 23:59:59'");
        }
        if (!empty($_GET['sna_join'])){
            $sna_join = $_GET['sna_join'];
            $this->leftJoin('tbl_company', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        }
        $this->orderBy('sna_id DESC');
        // echo $this->createCommand()->sql;exit;
        return $this;
    }

    public function getLastUpload($id)
    {
        $this->andWhere('sna_acc_id = :id',[
                ':id' => $id
            ]);
        $this->orderBy('sna_id DESC');
        $this->limit(1);
        return $this;
    }

    public function saveNext($id,$ctr)
    {
        $this->leftJoin('tbl_account', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('sna_id < :id', [':id' => $id]);
        $this->andWhere('acc_cty_id = :ctr',[
                ':ctr' => $ctr
            ]);
        $this->andWhere('sna_status = 0');
        $this->orderBy('sna_id DESC');
        $this->limit(1);
        return $this->one();
    }

    public function maxDuplicateReceipt($t,$u,$c)
    {
        $this->andWhere('date(from_unixtime(sna_transaction_time)) = :time',[
                ':time' => date('Y-m-d', strtotime($t))
            ]);
        $this->andWhere('sna_acc_id = :acc',[
                ':acc' => $u
            ]);
        $this->andWhere('sna_com_id = :com',[
                ':com' => $c
            ]);
        $this->andWhere("sna_transaction_time != 0");
        $this->all();

        return $this;
    }

    public function setChartTopFour()
    {
        $userId = $_POST['id'];
        $this->select('sna_cat_id,cat_name as category, count(*) as total_cat');
        $this->leftJoin('tbl_category','tbl_category.cat_id = sna_cat_id');
        $this->where('sna_acc_id = :id',[
            ':id' => $userId
        ]);
        if (!empty($_POST['chart_daterange'])){
            $sna_daterange = explode(' to ',($_POST['chart_daterange']));
                if($country == 'MY'){
                    $timezone = 8;
                } else {
                   $timezone = 7;
                }
            $this->andWhere("FROM_UNIXTIME(sna_upload_date) BETWEEN '$sna_daterange[0] 00:00:00' AND '$sna_daterange[1] 23:59:59'");
        }
        $this->groupBy('sna_cat_id');
        $this->orderBy('total_cat DESC');
        $this->limit(4);
        return $this->all();
    }
}
