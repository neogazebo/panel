<?php
    namespace app\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\data\ArrayDataProvider;
    // use yii\data\ActiveDataProvider;
    use app\controllers\BaseController;
    use app\models\SnapEarn;

    /**
     *
     */
    class DashboardController extends BaseController
    {

        public function actionIndex()
        {
            //$model = SnapEarn::find()->getUniqueUser();
            $data = SnapEarn::getDashboardModel()->getReceiptUploadUniqueUser();
            $model = SnapEarn::getDashboardModel()->getReceiptUploadData($data);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $model,
                // 'query' => $model,
                'pagination' => [
                    'pageSize' => 5
                ]
            ]);

            return $this->render('index',[
                'dataProvider' => $dataProvider
            ]);
        }

        public function actionLineChart()
        {
            if (Yii::$app->request->isAjax)
            {
                $upload_data = SnapEarn::getDashboardModel()->getUploadChartData();
                $idChrt = SnapEarn::getDashboardModel()->getUploadDataByCountry($upload_data, 'ID');
                $myChrt = SnapEarn::getDashboardModel()->getUploadDataByCountry($upload_data, 'MY');

                $status_data = SnapEarn::getDashboardModel()->getStatusChartData();
                $appIdChart = SnapEarn::getDashboardModel()->getUploadDataByStatus($status_data, 'ID', SnapEarn::STATUS_APPROVED);
                $appMyChart = SnapEarn::getDashboardModel()->getUploadDataByStatus($status_data, 'MY', SnapEarn::STATUS_APPROVED);
                $rjtIdChart = SnapEarn::getDashboardModel()->getUploadDataByStatus($status_data, 'ID', SnapEarn::STATUS_REJECTED);
                $rjtMyChart = SnapEarn::getDashboardModel()->getUploadDataByStatus($status_data, 'MY', SnapEarn::STATUS_REJECTED);

                $out = [];

                foreach ($idChrt as $id_chart) 
                {
                    $out[]['labels'] = $id_chart['tanggal'];
                    $out[]['id'] = $id_chart['jumlah'];
                }

                foreach ($myChrt as $my_chart) 
                {
                    $out[]['my'] = $my_chart['jumlah'];
                }

                foreach ($appIdChart as $apId) 
                {
                    $out[]['appId'] = $apId['jumlah'];
                }

                foreach ($appMyChart as $apMy) {
                    $out[]['appMy'] = $apMy['jumlah'];
                }

                foreach ($rjtIdChart as $rjtId) 
                {
                    $out[]['rjtId'] = $rjtId['jumlah'];
                }
                
                // get Rejected receipt Malaysia
                foreach ($rjtMyChart as $rjtMy) 
                {
                    $out[]['rjtMy'] = $rjtMy['jumlah'];
                }

                $filters = '';

                /*
                $idChrt = SnapEarn::find()->uploadIdChart($filters)->all();
                $myChrt = SnapEarn::find()->uploadMyChart($filters)->all();
                $appIdChart = SnapEarn::find()->approveIdChart($filters)->all();
                $appMyChart = SnapEarn::find()->approveMyChart($filters)->all();
                $rjtIdChart = SnapEarn::find()->rejectIdChart($filters)->all();
                $rjtMyChart = SnapEarn::find()->rejectMyChart($filters)->all();
                */

                // get upload receipt Indonesia
                /*
                foreach ($idChrt as $l) {
                    $out[]['labels'] = $l->tanggal;
                    $out[]['id'] = $l->jumlah;
                }

                // get upload receipt Malaysia
                foreach ($myChrt as $m) {
                    $out[]['my'] = $m->jumlah;
                }
                // get Approved receipt Indonesia
                foreach ($appIdChart as $apId) {
                    $out[]['appId'] = $apId->jumlah;
                }
                // get Approved receipt Malaysia
                foreach ($appMyChart as $apMy) {
                    $out[]['appMy'] = $apMy->jumlah;
                }
                // get Rejected receipt Indonesia
                foreach ($rjtIdChart as $rjtId) {
                    $out[]['rjtId'] = $rjtId->jumlah;
                }
                // get Rejected receipt Malaysia
                foreach ($rjtMyChart as $rjtMy) {
                    $out[]['rjtMy'] = $rjtMy->jumlah;
                }
                */

                //var_dump($out);
                //die;

                echo \yii\helpers\Json::encode($out);
            }
        }

        protected function findModel()
        {

        }
    }
