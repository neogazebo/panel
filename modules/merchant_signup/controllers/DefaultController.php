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
use app\components\helpers\General;
use app\models\Tag;
use app\models\FeatureSubscription;

/**
 * Default controller for the `MerchantSignup` module
 * @author Ganjar Setia M <ganjar.setia@ebizu.com>
 */
class DefaultController extends BaseController
{
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
                ', [
                    ':search' => '%' . $search . '%'
                ])
                ->orderBy(['id' => SORT_DESC]);
        } else {
            $model = MerchantSignup::find()->orderBy(['id' => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'sort' => false,
            'pagination' => [
                'pageSize' => 20,
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
        $user = new MerchantUser();
        $user->scenario = 'signup';
        $user->usr_password = md5('123456');
        $user->usr_type_id = 2;
        $user->usr_approved = 1;

        $model_company = new Company();

        // ajax validation
        if (Yii::$app->request->isAjax && $model_company->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model_company);
        }

        if ($model_company->load(Yii::$app->request->post())) {
            $changed_attributes = $model_company->getChangedAttribute(['com_timezone', 'com_in_mall', 'com_mac_id']);
            $user->usr_email = $model_company->com_email;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($user->save()) {
                    $model_company->com_usr_id = $user->usr_id;
                    $model_company->com_status = 1;
                    $model_company->com_snapearn = 1;
                    /* create from [ 
                        1= ADM panel; 
                        2 = Snapearn add new merchant; 
                        3 = Mall cms 
                        4 = merchant - signup
                        ]
                    */
                    $model_company->com_create_from = 4;
                    $model_company->com_snapearn_checkin = 1;
                    $model_company->com_registered_to = 'EBC';
                    $mall_id = Yii::$app->request->post('mall_id');
                    if (!empty($mall_id)) {
                        $mall = Mall::findOne($mall_id)->mal_name;
                        $model_company->com_name = $model_company->com_name .' @ ' . $mall;
                    }
                    if ($model_company->save()) {
                        $model_company->setTag();
                        $model->mer_login_email = $model_company->com_email;
                        $model->mer_company_name = $model_company->com_name;
                        $model->mer_bussiness_description = $model_company->com_description;
                        $model->mer_office_phone = $model_company->com_phone;
                        $model->mer_office_fax = $model_company->com_fax;
                        $model->mer_reviewed = Yii::$app->user->id;
                        if ($model->save()) {
                            if ($model_company->com_in_mall = 1) {
                                $mam_model = new MallMerchant();
                                $mam_model->mam_com_id = $model_company->com_id;
                                $mam_model->mam_mal_id = Yii::$app->request->post('mall_id');
                                $mam_model->save(false);
                            }
                            // $audit = AuditReport::setAuditReport('Copy Merchant from tbl_signup_merchant : ' . $model->mer_company_name, Yii::$app->user->id, MerchantSignup::className(), $model->id, $changed_attributes);
                            $activities = [
                                'Merchant Sign Up',
                                'Merchant Sign Up - Review, ' . $model_company->com_email . ' on ' . $model_company->com_name,
                                Company::className(),
                                $model_company->com_id
                            ];
                            $this->saveLog($activities);

                            $transaction->commit();
                            $this->setMessage('save','success', 'Business updated successfully!');
                            return $this->redirect([$this->getRememberUrl()]);
                        } else {
                            $transaction->rollback();
                            $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                        }
                    } else {
                        $transaction->rollback();
                        $this->setMessage('save', 'error', General::extractErrorModel($model_company->getErrors()));
                    }
                } else {
                    $transaction->rollback();
                    $this->setMessage('save', 'error', General::extractErrorModel($user->getErrors()));
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $this->setMessage('save', 'error', General::extractErrorModel($ex->getErrors()));
            }
        }

        return $this->render('review',[
            'model' => $model,
            'model_company' => $model_company
        ]);
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

                $activities = [
                    'Merchant Sign Up',
                    'Merchant Sign Up - XReview, (' . $model->mer_login_email . ') ' . $model->mer_bussines_name . ' on ' . $model->created_date,
                    MerchantSignup::className(),
                    $model->com_id
                ];
                $this->saveLog($activities);

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
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model = City::find()->SearchCityList();
            $out['results'] = array_values($model);
            return $out;
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
