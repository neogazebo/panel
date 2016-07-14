<?php

namespace app\modules\logwork\controllers;

use Yii;
use yii\web\Controller;
use app\models\WorkingTime;
use app\models\SearchWorkingTime;
use app\models\User;
use app\models\PdfForm;
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
        $model = WorkingTime::find()->with('reason')->detailPoint($id);
        $username = User::findOne($id)->username;
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('view',[
            'dataProvider' => $dataProvider,
            'id' => $id,
            'username' => $username
        ]);
    }

    public function actionCancel($id)
    {
        return $this->redirect(['view', 'id' => $id]);
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

    public function actionReport($id)
    {
        $user = User::findOne($id);
        $model = new PdfForm();
        if($model->load(Yii::$app->request->post())) {
            $date = explode(' to ', $model->date_range);
            $first_date = $date[0] . ' 00:00:00';
            $last_date = $date[1] . ' 23:59:59';

            $query = WorkingTime::find()->getReport($id, $model->date_range);
            $preview = '_preview';
            $redirect = 'logwork/default/view?id=' . $id;
            $title = [
                'username' => $user->username,
                'country' => $user->country == 'ID' ? 'Indonesia' : 'Malaysia',
                'first_date' => Yii::$app->formatter->asDate($first_date),
                'last_date' => Yii::$app->formatter->asDate($last_date),
            ];

            return \app\components\helpers\PdfExport::export($title, $model, $query, $preview, $redirect);
        }

        $model->username = $id;
        return $this->render('report', [
            'model' => $model,
            'user' => $user
        ]);
    }
}
