<?php

namespace app\modules\merchant_type\controllers;

use Yii;
use app\controllers\BaseController;
use app\models\CompanyType;
use app\models\CompanyTypeSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for CompanyType model.
 */
class DefaultController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CompanyType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanyTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new CompanyType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new CompanyType();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
               $model->save();
               $activities = [
                    'Create Speciality type', 
                    'Create Merchant Speciality type = '.$model->com_type_name,
                    CompanyType::className(), 
                    $model->com_type_id
                ];
                $this->saveLog($activities);
               return $results = [
                    'success' => 0, 
                    'message' => 'success'
                ];
            } else {
                $errors = $model->errors;
                return $results = [
                    'error' => 1000, 
                    'message' => $errors
                ];
            }
        } 
    }

    /**
     * Updates an existing CompanyType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
               $model->save();
               $activities = [
                    'Update Speciality type', 
                    'Update Merchant Speciality type = '.$model->com_type_name,
                    CompanyType::className(), 
                    $model->com_type_id
                ];
                $this->saveLog($activities);
               return $results = [
                    'success' => 0, 
                    'message' => 'success'
                ];
            } else {
                $errors = $model->errors;
                return $results = [
                    'error' => 1000, 
                    'message' => $errors
                ];
            }
        } 
    }

    /**
     * Deletes an existing CompanyType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $model->com_type_name = $model->com_type_name;
        if ($model->delete()) {
           $activities = [
                'Delete Speciality type', 
                'Delete Merchant Speciality type = '.$model->com_type_name,
                CompanyType::className(), 
                $model->com_type_id
            ];
            $this->saveLog($activities);
            return $results = [
                    'success' => 0, 
                    'message' => 'Success'
                ];
        }else{
            return $results = [
                    'error' => 1000, 
                    'message' => $model->error
                ];
        }
    }

    /**
     * Finds the CompanyType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanyType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
