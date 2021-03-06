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
use app\models\SearchUser;
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
        $this->setRememberUrl();
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

    public function actionTerms()
    {
        return $this->renderPartial('terms');
    }

    public function actionPrivacy()
    {
        return $this->renderPartial('privacy');
    }

    public function actionCancel()
    {
        return $this->redirect([$this->getRememberUrl()]);
    }

    public function actionAddChild()
    {
        if (Yii::$app->request->isAjax) {
            $childs = Yii::$app->request->post('to');
            $revokes = Yii::$app->request->post('from');
            $user_id = Yii::$app->request->post('user_id');
            $username = User::findOne($user_id)->username;

            if(!empty($childs)) {
                $model = AuthAssignment::deleteAll(['user_id' => $user_id]);
                foreach($childs as $child) {
                    $model = new AuthAssignment;
                    $model->item_name = $child;
                    $model->user_id = $user_id;
                    if($model->save()) {
                        $activities = [
                            'User',
                            'User - Add Child, ' . $model->user->username . ' assigned to ' . $model->item_name,
                            AuthAssignment::className(),
                            $model->user_id
                        ];
                        $this->saveLog($activities);

                        $this->setMessage('save', 'success', 'Add "' . $username . '" assignment successfully!');
                    } else {
                        $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                    }
                }
            } else {
                $model = AuthAssignment::deleteAll(['user_id' => $user_id]);
                $this->setMessage('save', 'warning', 'No assignment saved!');
            }
            return $this->redirect(['detail?id=' . $user_id]);
        }
    }

}
