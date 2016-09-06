<?php

namespace app\modules\snapearn\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\SnapearnGroup;
use app\models\SnapearnGroupDetail;
use app\models\User;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * Group controller for the `snapearngroup` module
 */
class GroupController extends BaseController
{
	public function actionIndex()
	{
		$model = SnapearnGroup::find();
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

	public function actionList()
	{
		if (Yii::$app->request->isAjax) {
    		$model = SnapearnGroup::find()->getListGroup();
            $out = [];
            foreach ($model as $d) {
                $out[] = ['id' => $d->spg_id, 'value' => $d->spg_name];
            }
            echo \yii\helpers\Json::encode($out);
    	}
	}

    protected function activities($id, $title, $name)
    {
        $activities = [
            'Snap & Earn Group',
            'Snap & Earn Group - ' . $title . ', ' . $name,
            SnapearnGroup::className(),
            $id
        ];
        $this->saveLog($activities);
    }

    public function actionCreate()
    {
        $model = new SnapearnGroup;
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post())) {
        	$model->spg_created_by = Yii::$app->user->id;
            if ($model->save()) {
                $this->activities($model->spg_id, 'Create', $model->spg_name);
                $this->setMessage('save', 'success', 'Group has been successfully created!');
                return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('form', [
        	'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = SnapearnGroup::findOne($id);
        $model->setScenario('update');

        if ($model->load(Yii::$app->request->post())) {
        	$model->spg_updated_by = Yii::$app->user->id;
            if ($model->save()) {
                $this->activities($model->spg_id, 'Edit', $model->spg_name);
                $this->setMessage('save', 'success', 'Group has been successfully updated!');
                return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('form', [
        	'model' => $model
        ]);
    }

    public function actionUserList($id)
    {
    	$title = SnapearnGroup::findOne($id);
    	$model = SnapearnGroupDetail::find()->where('spgd_spg_id = :id', [':id' => $id])->all();
    	$users = User::find()->all();

    	return $this->render('list', [
    		'title' => $title,
    		'models' => $model,
    		'users' => $users
		]);
    }

    public function actionAddList()
    {
        if (Yii::$app->request->isAjax) {
            $childs = Yii::$app->request->post('to');
            $revokes = Yii::$app->request->post('from');
            $spg_id = Yii::$app->request->post('spg_id');
            $group = SnapearnGroup::findOne($spg_id)->spg_name;

            if (!empty($childs)) {
                $model = SnapearnGroupDetail::deleteAll(['spgd_spg_id' => $spg_id]);
                $spgid = '';
                $users = '';
                foreach($childs as $child) {
                    $model = new SnapearnGroupDetail;
                    $model->spgd_usr_id = $child;
                    $model->spgd_spg_id = $spg_id;
                    $model->save();
                    $spgid .= $spg_id . ', ';
                    $users .= $child . ', ';
                }
                $users = rtrim($users, ', ');

                $this->activities($spg_id, 'Add List', 'List ' . $group . ' are ' . $spgid . ' and users are ' . $users);
                $this->setMessage('save', 'success', 'Some operators in "' . $group . '" list has been successfully added!');
            } else {
                $model = SnapearnGroupDetail::deleteAll(['spgd_spg_id' => $spg_id]);
                $this->setMessage('save', 'warning', 'No operators saved!');
            }
            return $this->redirect(['user-list?id=' . $spg_id]);
        }
    }
}
