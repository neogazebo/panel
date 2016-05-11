<?php

namespace app\modules\rbac\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use app\models\AuthItem;
use app\models\AuthRule;
use app\models\AuthItemChild;
use app\models\AuthAssignment;
use app\controllers\BaseController;
use app\components\helpers\General;

/**
 * Default controller for the `rbac` module
 */
class IndexController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;
    const INACTIVE_STATUS = 0;

    public function actionIndex()
    {
        $this->setRememberUrl();
        $model = AuthItem::find()->with(['user'])->list;
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
        $model = new AuthItem();
        // ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->type = self::TYPE_ROLE;
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

    	if ($model->load(Yii::$app->request->post())) {
            $auth = $this->_role();
            $role = $auth->createRole($model->name);
            $role->description = $model->description;
            if($auth->add($role)) {
                $model = AuthItem::findOne($model->name);
                $model->created_by = Yii::$app->user->identity->id;
                if($model->save()) {
                    $this->setMessage('save', 'success', 'Role "'.$model->name.'" successfully created!');
                } else {
                    $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                }
                return $this->redirect($this->getRememberUrl());
            }
    	}

    	return $this->renderAjax('form', [
			'model' => $model,
		]);
    }

    public function actionDetail($name)
    {
        $lists = $this->allModel($name)->all();
        $models = AuthItemChild::findAll($name);
        $title = $name;

        return $this->render('detail', [
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
            $role = Yii::$app->request->post('role');
            $auth = $this->_role();
            $parent = $auth->getRole($role);

            foreach ($childs as $val) {
                $child = $auth->getPermission($val);
                if(!$child){
                    $child = $auth->getRole($val);
                }
                if (!$auth->hasChild($parent,$child)) {
                    $auth->addChild($parent,$child);
                }
            }

            foreach ($revokes as $val) {
                $revoke = $auth->getPermission($val);
                if(!$revoke){
                    $revoke = $auth->getRole($val);
                }
                if ($auth->hasChild($parent,$revoke)) {
                    $auth->removeChild($parent,$revoke);
                }
            }
            
            $result = [
                'status' => 'success',
                'name' => $role
            ];
            $this->setMessage('save', 'success', 'Update item child successfully');
            return \yii\helpers\Json::encode($result);
        }
    }

    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        $oldName = $model->name;
        // ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->type = self::TYPE_ROLE;
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->identity->id;
            if ($model->save()) {
                $parent = AuthItemChild::findOne($name);
                $child = AuthItemChild::find()->where("child = '$name'")->one();
                if(!empty($parent)) {
                    $parent->parent = $name;
                    $parent->save();
                } elseif(!empty($child)) {
                    $parent->child = $name;
                    $parent->save();
                }

                $assign = AuthAssignment::findOne($name);
                if(!empty($assign)) {
                    $assign->item_name = $name;
                    $assign->save();
                }

                $this->setMessage('save', 'success', 'Role "' . $oldName . '" successfully updated to ' . $model->name);
            } else {
                $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
            }
            return $this->redirect($this->getRememberUrl());
        }

        return $this->renderAjax('form', [
            'model' => $model,
        ]);
    }

    public function actionDelete($name)
    {
        if(Yii::$app->request->isAjax) {
            $model = $this->findModel($name);
            if (!empty($model)) {
                // $model->status = self::INACTIVE_STATUS;
                $result = [
                    'status' => 'error',
                    'name' => $name
                ];
                if($model->delete())
                    $result = [
                        'status' => 'success',
                        'name' => $name
                    ];
                return \yii\helpers\Json::encode($result);
            }
        }
    }

    public function actionGetPermission()
    {
        if(Yii::$app->request->isAjax) {
            $result = Yii::$app->getRoutes->generatePermission();
            return \yii\helpers\Json::encode($result);
        }
    }

    private function _role()
    {
        return Yii::$app->authManager;
    }

    private function allModel($name)
    {
        if (!empty($model = AuthItem::find()->where("name != '$name'")->orderBy('date(from_unixtime(created_at)) DESC'))) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
