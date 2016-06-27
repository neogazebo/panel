<?php

namespace app\modules\logwork\controllers;

use Yii;
use yii\web\Controller;
use app\models\WorkingTime;
use app\models\SearchWorkingTime;
use app\models\User;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * Default controller for the `logwork` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {        
    	// $searchModel = new SearchWorkingTime();
     //    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = WorkingTime::find()->getWorker();
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

    public function actionView($id)
    {
        $model = WorkingTime::find()->detailPoint($id);
        $username = User::findOne($id)->username;
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('view',[
            'dataProvider' => $dataProvider,
            'username' => $username
        ]);
    }

    public function actionTest()
    {
        return $this->render('invoice');
    }

    public function actionUserList()
    {
    	if (Yii::$app->request->isAjax) {
    		$model = User::find()->searchUser();
            $out = [];
            foreach ($model as $d) {
                $out[] = ['id' => $d->id,'value' => $d->username];
            }
            echo \yii\helpers\Json::encode($out);
    	}
    }

    public function actionDevision()
    {
    	if (Yii::$app->request->isAjax) {
    		# code...
    	}
    }
}
