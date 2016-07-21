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
		$model = RedemptionReference::find()->findCostume();
        $dataProvider =  new ActiveDataProvider([
            'query' => $model,
            'sort' => false,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
		return $this->render('reward', [
			'dataProvider' => $dataProvider
		]);
	}

	public function actionCash()
	{
		$model = CashvoucherRedeemed::find()->list;
        $dataProvider =  new ActiveDataProvider([
            'query' => $model,
            'sort' => false,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
		return $this->render('cash', [
			'dataProvider' => $dataProvider
		]);
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
}
