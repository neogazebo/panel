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
            exit('oke');
            $auth = $this->_role();
            $role = $auth->createRole($model->name);
            $role->description = $model->description;
            if($auth->add($role)) {
                $model = AuthItem::findOne($model->name);
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

            if(!empty($childs)) {
                foreach ($childs as $val) {
                    $child = $auth->getPermission($val);
                    if(!$child)
                        $child = $auth->getRole($val);

                    if (!$auth->hasChild($parent, $child))
                        $auth->addChild($parent, $child);
                }
            }

            if(!empty($revokes)) {
                foreach ($revokes as $val) {
                    $revoke = $auth->getPermission($val);
                    if(!$revoke)
                        $revoke = $auth->getRole($val);

                    if ($auth->hasChild($parent, $revoke))
                        $auth->removeChild($parent, $revoke);
                }
            }

            $result = [
                'status' => 'success',
                'name' => $role
            ];
            $this->setMessage('save', 'success', 'Edit item child successfully');
            return $this->redirect(['detail?name=' . $role]);
        }
    }

    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        $oldName = $model->name;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->type = self::TYPE_ROLE;
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $auth = $this->_role();
            $admin = $auth->createRole($model->name);
            $auth->update($model->name, $admin);
            if ($model->save()) {
                $this->setMessage('save', 'success', 'Role "' . $oldName . '" successfully updated to ' . $model->name);
            } else {
                $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
            }
            return $this->redirect($this->getRememberUrl());
        } else {
             return $this->renderAjax('form', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($name)
    {
        if(Yii::$app->request->isAjax) {
            $model = $this->findModel($name);
            $auth = $this->_role();
            $admin = $auth->createRole($model->name);
            if ($auth->remove($admin) ) {
                $this->setMessage('save', 'success', 'Role "' . $name . '" successfully deleted');
            } else {
                $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
            }
            return $this->redirect(['index']);
        }
    }

    public function actionGetPermission($role)
    {
        if(Yii::$app->request->isAjax) {
            Yii::$app->getRoutes->generatePermission();
            $this->setMessage('save', 'success', 'Update list item successfully');
            return $this->redirect(['detail?name='.$role]);
            return \yii\helpers\Json::encode($result);
        }
    }

    public function actionCancel()
    {
        return $this->redirect([$this->getRememberUrl()]);
    }

    public function actionUser($name)
    {
        $model = AuthAssignment::find()
            ->where('
                item_name = :item
            ', [
                ':item' => $name
            ]);
        $model->joinWith(['user' => function($model) {
            $model->from(['user' => 'tbl_admin_user']);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('user', [
            'dataProvider' => $dataProvider,
            'name' => $name
        ]);
    }

    private function _role()
    {
        return Yii::$app->authManager;
    }

    private function allModel($name)
    {
        if (!empty($model = AuthItem::find()->getListPermission($name))) {
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
