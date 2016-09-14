<?php

namespace app\modules\com_speciality\controllers;

use Yii;
use app\models\Company;
use app\models\CompanySpeciality;
use app\models\Country;
use app\modules\com_speciality\models\MerchantSetSpeciality;
use app\modules\com_speciality\models\MerchantSpecialitySearchChanel;
use app\modules\system\processors\merchant_hq\MerchantHqChildrenSaveProcessor;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DeafultController implements the CRUD actions for CompanySpeciality model.
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
     * Lists all CompanySpeciality models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CompanySpeciality::find()->with('promo','type'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetail()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CompanySpeciality::find(),
        ]);

        return $this->render('detail', [
            'dataProvider' => $dataProvider,
        ]);   
    }

    /**
     * Displays a single CompanySpeciality model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGroup($id)
    {
        $type = CompanySpeciality::find()->with('type','country')->where('com_spt_id = :id',[
            ':id' => $id])->one();
        $title = $type->type->com_type_name.' ('.$type->country->cty_currency_name_iso3.')';
        $active_group = Company::find()
            ->select('com_id,com_name,com_country_id,com_currency,com_speciality')
            ->where('com_speciality = :type_id AND com_currency = :type_cty',[
                ':type_id' => $type->com_spt_id,
                ':type_cty' => $type->country->cty_currency_name_iso3
            ])
            ->andWhere('com_status = :status',[
                ':status' => 1
            ])
            ->asArray()->all();

        return $this->render('group',[
                'active_group' => $active_group,
                'spt_id' => $id,
                // 'inactive_group' => $inactive_group,
                'title' => $title
            ]);
    }

    public function actionSearch()
    {
        $processor = new MerchantSpecialitySearchChanel();
        return $processor->process();
    }

    public function actionSetSpeciality()
    {               
        var_dump(json_decode(Yii::$app->request->post('children')));exit;
        var_dump(count(Yii::$app->request->post('children')));exit;
        $processor = new MerchantSetSpeciality();
        return $processor->process();
    }

    /**
     * Creates a new CompanySpeciality model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CompanySpeciality();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['detail']);
            }else{
                var_dump($model->getErrors());exit;
                $model->com_spt_created_date = date('Y-m-d',$model->com_spt_created_date);
                $model->com_spt_updated_date = date('Y-m-d',$model->com_spt_updated_date);
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CompanySpeciality model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['detail']);
            }else{
                var_dump($model->getErrors());exit;
                $model->com_spt_created_date = date('Y-m-d',$model->com_spt_created_date);
                $model->com_spt_updated_date = date('Y-m-d',$model->com_spt_updated_date);
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            
        } else {
            $model->com_spt_created_date = date('Y-m-d',$model->com_spt_created_date);
            $model->com_spt_updated_date = date('Y-m-d',$model->com_spt_updated_date);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CompanySpeciality model.
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
     * Finds the CompanySpeciality model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanySpeciality the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanySpeciality::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
