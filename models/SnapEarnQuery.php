<?php

namespace app\models;

use Yii;

use yii\db\Expression;
use app\components\helpers\DateRangeCarbon;
use app\components\helpers\Utc;

/**
 * This is the ActiveQuery class for [[AccountDevice]].
 *
 * @see AccountDevice
 */
class SnapEarnQuery extends \yii\db\ActiveQuery
{
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
        if(Yii::$app->permission_helper->checkRbac()) {
            return $this->findCostumeWithRbac();
        }

        $dt = new DateRangeCarbon();
        $superuser = Yii::$app->user->identity->superuser == 1;
        $this->innerJoin('tbl_account', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        if($superuser){
            if (!empty($_GET['sna_cty'])) {
                $sna_cty = $_GET['sna_cty'];
                $this->andWhere('tbl_account.acc_cty_id = :country', [':country' => $sna_cty]);
            }
        }else{
            $country = Yii::$app->user->identity->country;
            $sna_cty = ($country == 'MYR') ? 'MY' : 'ID';
            $this->andWhere('tbl_account.acc_cty_id = :country', [':country' => $sna_cty]);
        }

        
        $like_name = Yii::$app->request->get('sna_member');
        if ($like_name) {
            $get_member = Account::find()->select('acc_id')
                    ->andWhere(['LIKE','acc_screen_name',$like_name])
                    ->asArray()->all();
            $acc_id = [];
            foreach ($get_member as $key) {
                $acc_id[] = (int)$key['acc_id'];
            }
            $this->andWhere(['sna_acc_id' => $acc_id]);
        }

        if (!empty($_GET['member_email'])) {
            $member_email = $_GET['member_email'];
            $this->andWhere('tbl_account.acc_facebook_email = :member_email', [':member_email' => $member_email]);
        }

        if (!empty($_GET['sna_receipt'])) {
            $sna_receipt = $_GET['sna_receipt'];
            $this->andWhere(['LIKE', 'sna_ops_receipt_number', $sna_receipt]);
        }

        if (!empty($_GET['sna_status'])) {
            $sna_status = $_GET['sna_status'];
            switch ($sna_status) {
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
            $this->andWhere('sna_status = :status', [':status' => $sna_status]);
        }
        
        if (!empty($_GET['sna_daterange'])) {
            $sna_daterange = explode(' to ', ($_GET['sna_daterange']));
            $first = "(select sna_id from tbl_snapearn where sna_upload_date >= UNIX_TIMESTAMP('$sna_daterange[0] 00:00:00') limit 1)";
            $second = "(select sna_id from tbl_snapearn where sna_upload_date <= UNIX_TIMESTAMP('$sna_daterange[1] 23:59:59') order by sna_id desc limit 1)";
            $this->andWhere("sna_id BETWEEN $first AND $second");
        } else {
            $sna_daterange = explode(' to ', ($dt->getDay()));
            $first = "(select sna_id from tbl_snapearn where sna_upload_date >= UNIX_TIMESTAMP('$sna_daterange[0]') limit 1)";
            $second = "(select sna_id from tbl_snapearn where sna_upload_date <= UNIX_TIMESTAMP('$sna_daterange[1]') order by sna_id desc limit 1)";
            $this->andWhere("sna_id BETWEEN $first AND $second");
        }
        
        if (!empty($_GET['sna_join'])) {
            $sna_join = $_GET['sna_join'];
            $this->leftJoin('tbl_company', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        }
        
        // operator filter
        if (!empty($_GET['ops_name'])) {
            $operatorId = $_GET['ops_name'];
            $this->andWhere("sna_review_by = :ops", [':ops' => $operatorId]);
        }

        if (!empty($_GET['sna_company_tagging'])) {
            $sna_company_tagging = $_GET['sna_company_tagging'];
            $tagging = '';
            switch ($sna_company_tagging) {
                case 'Ebizu':
                    $tagging = 'sna_company_tagging > 0 AND sna_com_id > 0';
                    break;
                case 'Manis':
                    $tagging = 'sna_company_tagging = 0 AND sna_com_id > 0';
                    break;
                case 'Untagged':
                    $tagging = 'sna_company_tagging = 0 AND sna_com_id = 0';
                    break;
            }
            $this->andWhere($tagging);
        }
        
        // merchant filter
        if (!empty($_GET['com_name'])) {
            $merchantId = $_GET['com_name'];
            $company_is_hq = Company::find()->checkCompanyIsParent($merchantId);
            if($company_is_hq) {
                $children = array_map('intval', Company::find()->getChildMerchantsId($merchantId));
                $this->andWhere(['sna_com_id' => $children]);
            } else {
                $this->andWhere("sna_com_id = :com",[':com' => $merchantId]);
            }
        }
        return $this;
    }

    public function findCostumeWithRbac()
    {
        $dt = new DateRangeCarbon();
        
        $this->innerJoin('tbl_account', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');

        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][country]')) {
            if (!empty($_GET['sna_cty'])) {
                $sna_cty = $_GET['sna_cty'];
                $this->andWhere('tbl_account.acc_cty_id = :country', [':country' => $sna_cty]);
            }
        }
        
        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][member_field]')) {
            $like_name = Yii::$app->request->get('sna_member');
            if($like_name){
                $get_member = Account::find()->select('acc_id')
                        ->andWhere(['LIKE','acc_screen_name',$like_name])
                        ->asArray()->all();
                $acc_id = [];
                foreach ($get_member as $key) {
                    $acc_id[] = (int)$key['acc_id'];
                }
                $this->andWhere(['sna_acc_id' => $acc_id]);
            }
        }
        
        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][member_email]')) {
            if (!empty($_GET['member_email'])) {
                $member_email = $_GET['member_email'];
                $this->andWhere('tbl_account.acc_facebook_email = :member_email', [':member_email' => $member_email]);
            }
        }
        
        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][receipt_number]')) {
            if (!empty($_GET['sna_receipt'])) {
                $sna_receipt = $_GET['sna_receipt'];
                $this->andWhere(['LIKE', 'sna_ops_receipt_number', $sna_receipt]);
            }
        }
        
        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][receipt_status]')) {
            if (!empty($_GET['sna_status'])) {
                $sna_status = $_GET['sna_status'];

                switch ($_GET['sna_status']) 
                {
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

                $this->andWhere('sna_status = :status', [':status' => $sna_status]);
            }
        }
        
        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][date_range]')) {
            if (!empty($_GET['sna_daterange'])) {
                $sna_daterange = explode(' to ', ($_GET['sna_daterange']));
                $first = "(select sna_id from tbl_snapearn where sna_upload_date >= UNIX_TIMESTAMP('$sna_daterange[0] 00:00:00') limit 1)";
                $second = "(select sna_id from tbl_snapearn where sna_upload_date <= UNIX_TIMESTAMP('$sna_daterange[1] 23:59:59') order by sna_id desc limit 1)";
                $this->andWhere("sna_id BETWEEN $first AND $second");
            } else {
                $sna_daterange = explode(' to ', ($dt->getDay()));
                $first = "(select sna_id from tbl_snapearn where sna_upload_date >= UNIX_TIMESTAMP('$sna_daterange[0]') limit 1)";
                $second = "(select sna_id from tbl_snapearn where sna_upload_date <= UNIX_TIMESTAMP('$sna_daterange[1]') order by sna_id desc limit 1)";
                $this->andWhere("sna_id BETWEEN $first AND $second");
            }
        }
        
        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][operator]')) {
            if (!empty($_GET['ops_name'])) {
                $operatorId = $_GET['ops_name'];
                $this->andWhere("sna_review_by = :ops", [':ops' => $operatorId]);
            }
        }
        
        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][tagging]')) {
            if (!empty($_GET['sna_company_tagging'])) {
                $sna_company_tagging = $_GET['sna_company_tagging'];
                $tagging = '';
                switch ($sna_company_tagging) {
                    case 'Ebizu':
                        $tagging = 'sna_company_tagging > 0 AND sna_com_id > 0';
                        break;
                    case 'Manis':
                        $tagging = 'sna_company_tagging = 0 AND sna_com_id > 0';
                        break;
                    case 'Untagged':
                        $tagging = 'sna_company_tagging = 0 AND sna_com_id = 0';
                        break;
                }
                $this->andWhere($tagging);
            }
        }

        if (Yii::$app->permission_helper->processPermissions('Snapearn', 'Snapearn[Page_Components][merchant_field]')) {
            if (!empty($_GET['com_name'])) {
                $merchantId = $_GET['com_name'];

                $company_is_hq = Company::find()->checkCompanyIsParent($merchantId);
                
                if($company_is_hq) {
                    $children = array_map('intval', Company::find()->getChildMerchantsId($merchantId));
                    $this->andWhere(['sna_com_id' => $children]);
                } else {
                    $this->andWhere("sna_com_id = :com",[':com' => $merchantId]);
                }
            }
        }
        
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
        $this->andWhere('acc_cty_id = :ctr', [
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
            coc_name as categoryName,
            sum(sna_receipt_amount) as amount,
            acc_cty_id as country
        ");
        $this->innerJoin('tbl_company_category','tbl_company_category.coc_id = tbl_snapearn.sna_cat_id');
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
        if (!empty($_GET['dash_daterange'])) {
            $sna_daterange = explode(" to ", $_GET['dash_daterange']);
            $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
            $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
        } else {
            $sna_daterange = explode(' to ', ($dt->getThisWeek()));
        }

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
        if (!empty($_GET['dash_daterange'])) {
            $sna_daterange = explode(" to ", $_GET['dash_daterange']);
            $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
            $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
        } else {
            $sna_daterange = explode(' to ', ($dt->getThisWeek()));
        }

        $this->andWhere("FROM_UNIXTIME(sna_upload_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        $this->andWhere('tbl_account.acc_cty_id = :cty',[
            ':cty' => 'MY'
        ]);
        $this->groupBy('date(from_unixtime(sna_upload_date)) ');
        // echo $this->createCommand()->sql;exit;
        return $this;
    }

    public function approveIdChart($filters = '')
    {
        $dt = new DateRangeCarbon();
        $this->select('date(from_unixtime(sna_review_date)) as tanggal, count(*) as jumlah');
        if (!empty($_GET['dash_daterange'])) {
            $sna_daterange = explode(" to ", $_GET['dash_daterange']);
            $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
            $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
        } else {
            $sna_daterange = explode(' to ', ($dt->getThisWeek()));
        }

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
        if (!empty($_GET['dash_daterange'])) {
            $sna_daterange = explode(" to ", $_GET['dash_daterange']);
            $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
            $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
        } else {
            $sna_daterange = explode(' to ', ($dt->getThisWeek()));
        }

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

        if (!empty($_GET['dash_daterange'])) {
            $sna_daterange = explode(" to ", $_GET['dash_daterange']);
            $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
            $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
        } else {
            $sna_daterange = explode(' to ', ($dt->getThisWeek()));
        }

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
        if (!empty($_GET['dash_daterange'])) {
            $sna_daterange = explode(" to ", $_GET['dash_daterange']);
            $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
            $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
        } else {
            $sna_daterange = explode(' to ', ($dt->getThisWeek()));
        }

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
        $dt = new DateRangeCarbon();
        $this->select(new Expression("
            STR_TO_DATE(yearweek(from_unixtime(sna_upload_date), 3), '%Y%d') as weeks, 
            count(distinct sna_acc_id) as total_unique, 
            count(distinct IF(acc_cty_id = 'ID', sna_acc_id, null)) as total_unique_user_id,
            count(distinct IF(acc_cty_id = 'MY', sna_acc_id, null)) as total_unique_user_my
            "));
        $this->innerJoin('tbl_account', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');
        if (!empty($_GET['dash_daterange'])) {
            $sna_daterange = explode(" to ", $_GET['dash_daterange']);
            $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
            $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
        } else {
            $sna_daterange = explode(' to ', ($dt->getThisWeek()));
        }

        $this->andWhere("DATE(FROM_UNIXTIME(sna_upload_date)) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
        $this->groupBy(new Expression('yearweek(from_unixtime(sna_upload_date),3)'));
        return $this->asArray()->all();
    }

    public function getExcelColumns()
    {
        return  [
            'A' => [
                'name' => 'Merchant',
                'width' => 30,
                'height' => 5,
                'db_column' => 'merchant',
                'have_relations' => true,
                'relation_name' => 'com_name'
            ], 
            'B' => [
                'name' => 'Member',
                'width' => 30,
                'height' => 5,
                'db_column' => 'member',
                'have_relations' => true,
                'relation_name' => 'acc_screen_name'
            ], 
            'C' => [
                'name' => 'Member Email',
                'width' => 30,
                'height' => 5,
                'db_column' => 'member',
                'have_relations' => true,
                'relation_name' => 'acc_facebook_email'
            ], 
            'D' => [
                'name' => 'Ops Receipt Number',
                'width' => 30,
                'height' => 5,
                'db_column' => 'sna_ops_receipt_number',
            ], 
            'E' => [
                'name' => 'Receipt Date',
                'width' => 30,
                'height' => 5,
                'db_column' => 'sna_receipt_date',
            ],
            'F' => [
                'name' => 'Receipt Amount',
                'width' => 30,
                'height' => 5,
                'db_column' => 'sna_receipt_amount',
            ],
            'G' => [
                'name' => 'Point',
                'width' => 30,
                'height' => 5,
                'db_column' => 'sna_point',
            ],
            'H' => [
                'name' => 'Upload Date',
                'width' => 30,
                'height' => 5,
                'db_column' => 'sna_upload_date',
                'format' => function($data) {
                    return Yii::$app->formatter->asDateTime(Utc::convert($data));
                }
            ],
            'I' => [
                'name' => 'Review Date',
                'width' => 30,
                'height' => 5,
                'db_column' => 'sna_review_date',
                'format' => function($data) {
                    return Yii::$app->formatter->asDateTime(Utc::convert($data));
                }
            ],
            'J' => [
                'name' => 'Operator',
                'width' => 30,
                'height' => 5,
                'db_column' => 'review',
                'have_relations' => true,
                'relation_name' => 'username'
            ],
            'K' => [
                'name' => 'Status',
                'width' => 30,
                'height' => 5,
                'db_column' => 'sna_status',
                'format' => function($data) {
                    if ($data == 1) 
                    {
                        return "Approved";
                    } 
                    elseif ($data == 2) 
                    {
                        return "Rejected";
                    } 
                    else 
                    {
                        return "New";
                    }
                }
            ],
            'L' => [
                'name' => 'Description',
                'width' => 30,
                'height' => 5,
                'db_column' => 'remark',
                'have_relations' => true,
                'relation_name' => 'sem_remark'
            ]
        ];
    }

    public function getExcelColumnsStyles()
    {
        return [
            'font' => [
                 'bold'  => true,
            ]
        ];
    }
}
