<?php

namespace app\modules\voucher\controllers;

use Yii;
use app\controllers\BaseController;
use app\models\RedemptionReference;
use app\models\CashvoucherRedeemed;
use app\models\CashvoucherRedeemedSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * Default controller for the `voucher` module
 * 
 * @author Ilham Fauzi <ilham@ebizu.com>
 */
class DefaultController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionReward()
    {
        $this->processOutputType();
        $this->processOutputSize(\Yii::$app->request->get('limit'));

        $model = RedemptionReference::find()->findCostume();

        $this->data_provider =  new ActiveDataProvider([
            'query' => $model,
            'sort' => false,
            'pagination' => [
                'pageSize' => \Yii::$app->request->get('limit')
            ]
        ]); 

        $columns = RedemptionReference::find()->getExcelColumns();
        $column_styles = RedemptionReference::find()->getExcelColumnsStyles();

        $filename = 'Reward-Reference-' . date('Y-m-d-H-i-s', time()) . '.xlsx';

        $view_filename = 'reward';
        $save_path = 'reward';

        return $this->processOutput($view_filename, $columns, $column_styles, $save_path, $filename);
    }

    public function actionCash()
    {
        $this->processOutputType();
        $this->processOutputSize(\Yii::$app->request->get('limit'));

        $model = CashvoucherRedeemed::find()->list;

        $this->data_provider =  new ActiveDataProvider([
            'query' => $model,
            'sort' => false,
            'pagination' => [
                'pageSize' => \Yii::$app->request->get('limit')
            ]
        ]);

        $columns = CashvoucherRedeemed::find()->getExcelColumns();
        $column_styles = CashvoucherRedeemed::find()->getExcelColumnsStyles();

        $filename = 'Cash-Voucher-' . date('Y-m-d-H-i-s', time()) . '.xlsx';

        $view_filename = 'cash';
        $save_path = 'cash_voucher';

        return $this->processOutput($view_filename, $columns, $column_styles, $save_path, $filename);
    }

    public function actionUserList()
    {
    	if (Yii::$app->request->isAjax) {
    		$model = \app\models\Account::find()->findAccount();
            $out = [];
            foreach ($model as $d) {
                $out[] = ['id' => $d->acc_id,'value' => $d->acc_screen_name];
            }
            echo \yii\helpers\Json::encode($out);
    	}
    }

    public function actionGetReward($q = null)
    {
        if (!is_null($q)) {
            $model = RedemptionReference::find()->getReward($q);
            $data = $model->asArray()->all();
            $out = [];
            foreach ($data as $d) {
                $out[] = ['id' => $d['id'],'value' => $d['text']];
            }
            echo \yii\helpers\Json::encode($out);
        }
        
    }
}
