<?php

namespace app\modules\logwork\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\SnapearnPoint;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * Point controller for the `logwork` module
 */
class PointController extends BaseController
{
    public function actionIndex()
    {
        $model = SnapearnPoint::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new SnapearnPoint;
        if ($model->load(Yii::$app->request->post())) {
        	$model->spo_created_by = Yii::$app->user->id;
            if ($model->save()) {
                $activities = [
                    'SnapearnPoint',
                    'SnapearnPoint - Add New Point, "' . $model->spo_name . '" with point is ' . $model->spo_point . ' has been added!',
                    SnapearnPoint::className(),
                    $model->spo_created_by
                ];
                $this->saveLog($activities);

                $this->setMessage('save', 'success', 'Point has been successfully created!');
                return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('form', [
        	'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = SnapearnPoint::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
        	$model->spo_updated_by = Yii::$app->user->id;
            if ($model->save()) {
                $activities = [
                    'SnapearnPoint',
                    'SnapearnPoint - Add New Point, "' . $model->spo_name . '" with point is ' . $model->spo_point . ' has been edited!',
                    SnapearnPoint::className(),
                    $model->spo_updated_by
                ];
                $this->saveLog($activities);

                $this->setMessage('save', 'success', 'Point has been successfully updated!');
                return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('form', [
        	'model' => $model
        ]);
    }

}