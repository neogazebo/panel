<?php

    namespace app\models;

    use Yii;

    use yii\db\Expression;
    use app\components\helpers\DateRangeCarbon;

    /**
     * This is the ActiveQuery class for [[AccountDevice]].
     *
     * @see AccountDevice
     */
    class SnapEarnDashboardQuery extends \yii\db\ActiveQuery
    {
        public function getUploadChartData()
        {
            $results = [];

            $dt = new DateRangeCarbon();

            $this->select('
                date(from_unixtime(sna_upload_date)) as tanggal, 
                sna_upload_date,
                acc_cty_id'
            );

            if (!empty($_GET['dash_daterange'])) 
            {
                $sna_daterange = explode(" to ", $_GET['dash_daterange']);
                $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
                $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
            } 
            else 
            {
                $sna_daterange = explode(' to ', ($dt->getThisWeek()));
            }

            $this->andWhere("FROM_UNIXTIME(sna_upload_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
            $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');

            return $this->asArray()->all();
        }

        public function getStatusChartData()
        {
            $results = [];

            $dt = new DateRangeCarbon();

            $this->select('
                date(from_unixtime(sna_review_date)) as tanggal, 
                sna_review_date,
                acc_cty_id,
                sna_status as status'
            );

            if (!empty($_GET['dash_daterange'])) 
            {
                $sna_daterange = explode(" to ", $_GET['dash_daterange']);
                $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
                $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
            } 
            else 
            {
                $sna_daterange = explode(' to ', ($dt->getThisWeek()));
            }

            $this->andWhere("FROM_UNIXTIME(sna_review_date) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");
            $this->innerJoin('tbl_account','tbl_account.acc_id = tbl_snapearn.sna_acc_id');

            return $this->asArray()->all();
        }

        public function getReceiptUploadUniqueUser()
        {
            $results = [];

            $dt = new DateRangeCarbon();

            $this->select(new Expression(
                "sna_upload_date, 
                sna_acc_id, 
                acc_cty_id, 
                STR_TO_DATE(yearweek(from_unixtime(sna_upload_date), 3), '%Y%d') as weeks"
            ));

            $this->innerJoin('tbl_account', 'tbl_account.acc_id = tbl_snapearn.sna_acc_id');

            if (!empty($_GET['dash_daterange'])) 
            {
                $sna_daterange = explode(" to ", $_GET['dash_daterange']);
                $sna_daterange[0] = $sna_daterange[0] . ' 00:00:00';
                $sna_daterange[1] = $sna_daterange[1] . ' 23:59:59';
            } 
            else 
            {
                $sna_daterange = explode(' to ', ($dt->getThisWeek()));
            }

            $this->andWhere("DATE(FROM_UNIXTIME(sna_upload_date)) BETWEEN '$sna_daterange[0]' AND '$sna_daterange[1]'");

            return $this->asArray()->all();
        }
        
        public function getUploadDataByCountry($data, $country)
        {
            $results = [];
            $upload_dates = [];

            $i = 0;

            foreach($data as $d)
            {
                array_push($upload_dates, $d['tanggal']);
            }

            $upload_dates_unq = array_values(array_unique($upload_dates));

            sort($upload_dates_unq);

            foreach($upload_dates_unq as $date)
            {
                $uploads = $this->filterReceiptByCountry($date, $data, $country);

                if(count($uploads) > 0)
                {
                    $output = [
                        'tanggal' => $date,
                        'jumlah' => count($uploads)

                    ];
                    
                    array_push($results, $output);
                }
            }   

            return $results;
        }

        public function getUploadDataByStatus($data, $country, $status)
        {
            $results = [];
            $upload_dates = [];

            $i = 0;

            foreach($data as $d)
            {
                array_push($upload_dates, $d['tanggal']);
            }

            $upload_dates_unq = array_values(array_unique($upload_dates));

            sort($upload_dates_unq);

            //var_dump($upload_dates_unq);

            foreach($upload_dates_unq as $date)
            {
                $uploads = $this->filterReceiptByCountryAndStatus($date, $data, $country, $status);

                if(count($uploads) > 0)
                {
                    $output = [
                        'tanggal' => $date,
                        'jumlah' => count($uploads)

                    ];
                    
                    array_push($results, $output);
                }
            }   

            return $results;
        }

        public function getReceiptUploadData($data)
        {
            $results = [];
            $weeks = [];
            $id_user = [];
            $my_user = [];
            $users = [];

            $data = $this->getReceiptUploadUniqueUser();

            foreach($data as $d)
            {
                array_push($weeks, $d['weeks']);
            }

            $weeks_unq = array_values(array_unique($weeks));

            sort($weeks_unq);

            foreach($weeks_unq as $week_key => $w)
            {
                $id_user = Yii::$app->array_helper->sortUniqueByValue($this->filterUniqueUserByCountry($w, $data, 'ID'), 'sna_acc_id');
                $my_user = Yii::$app->array_helper->sortUniqueByValue($this->filterUniqueUserByCountry($w, $data, 'MY'), 'sna_acc_id');

                $output = [
                    'weeks' => $w,
                    'total_unique' => count($id_user) + count($my_user),
                    'total_unique_user_id' => count($id_user),
                    'total_unique_user_my' => count($my_user)

                ];
                
                array_push($results, $output);
            }   
            
            return $results;
        }

        private function filterUniqueUserByCountry($week, $data, $country)
        {
            $users = [];

            foreach($data as $d)
            {
                if($d['weeks'] == $week)
                {
                    if($d['acc_cty_id'] == $country)
                    {
                        array_push($users, $d);
                    }
                }

            }

            return $users;
        }

        private function filterReceiptByCountry($date, $data, $country)
        {
            $uploads = [];

            foreach($data as $d)
            {
                if($d['tanggal'] == $date && $d['acc_cty_id'] == $country)
                {
                    array_push($uploads, $d);
                }

            }
            return $uploads;
        }

        private function filterReceiptByCountryAndStatus($date, $data, $country, $status)
        {
            $uploads = [];

            foreach($data as $d)
            {
                if($d['tanggal'] == $date && $d['status'] == $status && $d['acc_cty_id'] == $country)
                {
                    array_push($uploads, $d);
                }

            }
            return $uploads;
        }
    }
