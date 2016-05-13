<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\AuthAssignment;
use app\models\AuthItemChild;
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

        return $this->render('index', [
    		'dataProvider' => $dataProvider
    	]);
    }

    public function actionDetail($id)
    {
        $lists = AuthItemChild::find()->groupBy('parent')->all();
        $models = AuthAssignment::find()->where('user_id = :id', [':id' => $id])->all();
        $title = User::findOne($id)->username;

        return $this->render('detail', [
            'lists' => $lists,
            'models' => $models,
            'title' => $title,
        ]);
    }

    public function actionAddChild()
    {
        if (Yii::$app->request->isAjax) {
            $result = [
                'status' => 'success',
                'name' => $rule
            ];
            $this->setMessage('save', 'success', 'Edit assignment successfully');
            return $this->redirect(['detail?name=' . $rule]);
        }
    }

    private function allModel($id)
    {
        if (!empty($model = User::find()->where("name != '$name'")->orderBy('date(from_unixtime(created_at)) DESC'))) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
