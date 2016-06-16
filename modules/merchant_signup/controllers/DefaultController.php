<?php

namespace app\modules\merchant_signup\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;
use app\components\helpers\S3;
use app\models\MallMerchant;
use app\models\MerchantSignup;
use app\models\MerchantSignupSearch;
use app\models\Company;
use app\models\City;
use app\models\Mall;
use app\models\User;
use app\models\MerchantUser;
use app\models\AuditReport;
use app\models\Tag;
use app\models\FeatureSubscription;

/**
 * Default controller for the `MerchantSignup` module
 * @author Ganjar Setia M <ganjar.setia@ebizu.com>
 */
class DefaultController extends BaseController
{
	private $_pageSize = 20;

	// public function behaviors()
 //    {
        // return [
        //     'verbs' => [
        //         'class' => VerbFilter::className(),
        //         'actions' => [
        //             'delete' => ['POST'],
        //         ],
        //     ],
        // ];
    // }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->setRememberUrl();
        if(Yii::$app->request->get('search')) {
            $search = Yii::$app->request->get('search');
            $model = MerchantSignup::find()
                ->where('
                    mer_bussines_name LIKE :search OR 
                    mer_company_name LIKE :search OR 
                    mer_bussiness_description LIKE :search OR 
                    mer_login_email LIKE :search OR 
                    mer_address LIKE :search OR 
                    mer_pic_name LIKE :search 
                ', [':search' => '%' . $search . '%']);
        } else
    		$model = MerchantSignup::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => $this->_pageSize,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCancel()
    {
        $this->redirect([$this->getRememberUrl()]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionReview($id)
    {
        $model = $this->findModel($id);
        $model_company = new Company();
        $user = new MerchantUser();
        $user->scenario = 'signup';
        $user->usr_password = md5('123456');
        $user->usr_type_id = 2;
        $user->usr_approved = 0;

        $model->mer_bussiness_description = \yii\helpers\Html::decode($model->mer_bussiness_description);
        $model_company->tag = $model_company->getTag($id);
        $set_reviewed = Yii::$app->user->id;

        // ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model_company->com_in_mall == 1) {
            if ($model_company->marchant instanceof MallMerchant) {
                $mall = Mall::findOne($model_company->marchant->mam_mal_id);
                if ($mall instanceof Mall) {
                    $model_company->isMallManaged = $mall->mal_key == true;
                    $unit_merchant = FloorPlanMallMerchant::listunit($model->marchant->mam_id);
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model_company->load(Yii::$app->request->post())) {
            var_dump($model_company);exit;
            $changed_attributes = $model_company->getChangedAttribute(['com_timezone', 'com_in_mall', 'com_mac_id']);
            $user->usr_email = $model_company->com_email;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($user->save(false)) {
                    $model_company->com_usr_id = $user->usr_id;
                    $model_company->com_email = $user->usr_email;
                    $model_company->com_status = 1;
                    $model_company->com_snapearn = 1;
                    $model_company->com_snapearn_checkin = 1;
                    $model_company->com_registered_to = 'EBC';
                    $model->mer_reviewed = $set_reviewed;
                    if ($model_company->save() && $model->save()) {
                        $audit = AuditReport::setAuditReport('update business : ' . $model->mer_company_name, Yii::$app->user->id, MerchantSignup::className(), $model->id, $changed_attributes);
                        if ($audit->save()) {
                           // \Yii::$app->session->set('company', '');
                            $model_company->setTag();
                            $transaction->commit();
                            $this->setMessage('save','success', 'Business updated successfully!');
                        }
                        return $this->redirect([$this->getRememberUrl()]);
                    }
                } else {
                    $error = $model_company->getErrors();
                    foreach ($error as $value) {
                        $this->setMessage('save','error', $value);
                    }
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                throw $ex;
            }
        }
        return $this->render('review', [
            'model' => $model,
            'model_company' => $model_company,
        ]);
        /*$model_company = new Company();
        $model_mall_merchant = new MallMerchant();
        $model_user = new User();

        $model_merchant_signup = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $post_data_company) {
            $post_data_company = Yii::$app->request->post();
            // TODO: isi post data company dari form merchant signup
            $model_company->load($post_data_company);
            if ($model->validate() && $model_company->validate() && $model_mall_merchant->validate() && $model_user->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model_company->save() && $model_mall_merchant->save() && $model_user->save()) {
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollback();
                        $this->setMessage('save','error', 'Something wrong while submit review. Please try again!');
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }
            } else {
                // error validation
                $this->setMessage('save','error', 'Something wrong while submit review. Please try again!');
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('review', [
                'model' => $model,
                'model_company' => $model_company,
                'model_mall_merchant' => $model_mall_merchant,
                'model_user' => $model_user,
            ]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('review', [
                'model' => $model,
                'model_company' => $model_company,
                'model_mall_merchant' => $model_mall_merchant,
                'model_user' => $model_user,
            ]);
        }
        /*$model = $this->findModel($id);
        $model_company = new Company();

        $model_merchant_signup = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $post_data_company) {
            $post_data_company = Yii::$app->request->post();
            // TODO: isi post data company dari form merchant signup
            $model_company->load($post_data_company);
            if ($model->validate() && $model_company->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model_company->save()) {
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollback();
                        $this->setMessage('save','error', 'Something wrong while submit review. Please try again!');
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw $e;
                }
            } else {
                // error validation
                $this->setMessage('save','error', 'Something wrong while submit review. Please try again!');
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('review', [
                'model' => $model,
                'model_company' => $model_company,
            ]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('review', [
                'model' => $model,
                'model_company' => $model_company,
            ]);
        } */
    }

    public function actionXreview($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'review';
        if($model->load(Yii::$app->request->post())) {
            $model->mer_reviewed = 1;
            if(!empty($model->mer_multichain_file)) {
                $model->mer_multichain_file = UploadedFile::getInstance($model, 'mer_multichain_file');
    
                $filename = time() . '.' . pathinfo($model->mer_multichain_file->name, PATHINFO_EXTENSION);
                $originalFile = $model->mer_multichain_file->tempName;
                $model->mer_multichain_file = $filename;
            }

            if($model->save()) {
                if(!empty($model->mer_multichain_file))
                    S3::Upload($filename, $originalFile);

                $this->setMessage('save', 'success', 'This merchant has been successfully edited!');
                return $this->redirect(['index']);
            }
        }

        return $this->render('form', [
            'model' => $model
        ]);
    }

    public function actionTaglist()
    {
        if (\Yii::$app->request->isAjax) {
            $response = [];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $tag_id = \Yii::$app->request->post('tag_id');
            $category = \Yii::$app->request->post('category');
            $response = Tag::find()->getCategoryTagList($category);
            return $response;
        }
    }

    public function actionSettag()
    {
        if (\Yii::$app->request->isAjax) {
            $response = ['success' => 0];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $tag_id = \Yii::$app->request->post('tag_id');
            if (!empty($tag_id)) {
                $value = Tag::findOne($tag_id)->tag_name;
                $response = ['success' => 1, 'data' => $tag_id, 'value' => $value];
            } else {
                $response = ['success' => 0];
            }
            return $response;
        }
    }

    public function actionRegister() {
        $fes_code = isset($_GET['reg']) ? $_GET['reg'] : 'EBC';
        return FeatureSubscription::packageList($fes_code);
    }

    protected function findModel($id)
    {
        if (($model = MerchantSignup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionCityList()
    {
        if (Yii::$app->request->isAjax){
            $model = City::find()->SearchCityList();
            echo \yii\helpers\Json::encode($model);
        }
    }

    public function actionMallList()
    {
        if (Yii::$app->request->isAjax){
            $model = Mall::find()->SearchMallList();
            $out = [];
            foreach ($model as $d) {
                $out[] = ['id' => $d->mal_id,'value' => $d->mal_name];
            }
            echo \yii\helpers\Json::encode($out);
        }
    }
}
