<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use app\models\SnapEarn;
/**
 *
 */
class DashboardController extends Controller
{

    public function actionIndex()
    {
        $model = SnapEarn::find()->getUniqueUser();

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
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
        if (Yii::$app->request->isAjax){
            $filters = '';
            $idChrt = SnapEarn::find()->uploadIdChart($filters)->all();
            $myChrt = SnapEarn::find()->uploadMyChart($filters)->all();
            $appIdChart = SnapEarn::find()->approveIdChart($filters)->all();
            $appMyChart = SnapEarn::find()->approveMyChart($filters)->all();
            $rjtIdChart = SnapEarn::find()->rejectIdChart($filters)->all();
            $rjtMyChart = SnapEarn::find()->rejectMyChart($filters)->all();
            $out = [];
                // get upload receipt Indonesia
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

            echo \yii\helpers\Json::encode($out);
        }
    }

    protected function findModel()
    {

    }
}
