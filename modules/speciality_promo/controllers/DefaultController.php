<?php

namespace app\modules\speciality_promo\controllers;

use Yii;
use app\models\ComSpecialityPromo;
use app\models\ComSpecialityPromoSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TestController implements the CRUD actions for ComSpecialityPromo model.
 */
class DefaultController extends Controller
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
     * Lists all ComSpecialityPromo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ComSpecialityPromoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ComSpecialityPromo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ComSpecialityPromo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new ComSpecialityPromo();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->spt_promo_start_date = strtotime($model->spt_promo_start_date);
                $model->spt_promo_end_date = strtotime($model->spt_promo_end_date);
                $model->save();
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
        // if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        //     Yii::$app->response->format = 'json';
        //     return \yii\widgets\ActiveForm::validate($model);
        // }
        
        // if ($model->load(Yii::$app->request->post())) {
        //     $model->spt_promo_start_date = strtotime($model->spt_promo_start_date);
        //     $model->spt_promo_end_date = strtotime($model->spt_promo_end_date);
        //     if ($model->save()) {
        //         return $this->redirect(['index']);
        //     }else{
        //         var_dump($model->getErrors());exit;
        //         $model->spt_promo_start_date = date('Y-m-d',$model->spt_promo_start_date);
        //         $model->spt_promo_end_date = date('Y-m-d',$model->spt_promo_end_date);
        //         return $this->render('create', [
        //             'model' => $model,
        //         ]);
        //     }
        // } else {
        //     return $this->render('create', [
        //         'model' => $model,
        //     ]);
        // }
    }

    /**
     * Updates an existing ComSpecialityPromo model.
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
                $model->spt_promo_start_date = strtotime($model->spt_promo_start_date);
                $model->spt_promo_end_date = strtotime($model->spt_promo_end_date);
                $model->save();
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
     * Deletes an existing ComSpecialityPromo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the ComSpecialityPromo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ComSpecialityPromo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ComSpecialityPromo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
