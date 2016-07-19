<?php

namespace app\modules\voucher\controllers;

use Yii;
use app\controllers\BaseController;
use app\models\RedemptionReference;
use app\models\CashvoucherRedeemed;
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
		$model = RedemptionReference::find();
        $dataProvider =  new ActiveDataProvider([
            'query' => $model,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
		return $this->render('reward', [
			'dataProvider' => $dataProvider
		]);
	}

	public function actionCash()
	{
		$model = CashvoucherRedeemed::find();
        $dataProvider =  new ActiveDataProvider([
            'query' => $model,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
		return $this->render('cash', [
			'dataProvider' => $dataProvider
		]);
	}
}
