<?php

namespace app\modules\users\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\AuthAssignment;
use app\models\User;
use app\controllers\BaseController;

/**
 * Default controller for the `users` module
 */
class IndexController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	$model = User::find()->where("type = 1")->orderBy('id DESC');
    	$dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('index',[
        		'dataProvider' => $dataProvider
        	]);
    }
}
