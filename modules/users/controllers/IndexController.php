<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\AuthAssignment;
use app\models\AuthItemChild;
use app\models\AuthRule;
use app\models\User;
use app\controllers\BaseController;
use app\modules\rbac\controllers\AdminRule;
use app\components\helpers\General;

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
            'id' => $id,
            'lists' => $lists,
            'models' => $models,
            'title' => $title,
        ]);
    }

    public function actionAddChild()
    {
        if (Yii::$app->request->isAjax) {
            $childs = Yii::$app->request->post('to');
            $revokes = Yii::$app->request->post('from');
            $user_id = Yii::$app->request->post('user_id');
            $username = User::findOne($user_id)->username;

            $auth = Yii::$app->authManager;
            if(!empty($childs)) {
                foreach($childs as $child) {
                    $model = AuthAssignment::find()
                        ->where(['item_name' => $child, 'user_id' => $user_id])
                        ->one();
                    if(empty($model)) {
                        $model = new AuthAssignment;
                        $model->item_name = $child;
                        $model->user_id = $user_id;
                        if($model->save())
                            $this->setMessage('save', 'success', 'Add "' . $username . '" assignment successfully!');
                        else
                            $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                    }
                } else {
                    $this->setMessage('save', 'warning', '"' . $username . '" assignment already exist!');
                }
            } else {
                $this->setMessage('save', 'info', 'No assignment saved!');
            }
            return $this->redirect(['detail?id=' . $user_id]);
        }
    }

}
