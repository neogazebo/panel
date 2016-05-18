<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use Aws\S3\S3Client;
use yii\web\Response;
use yii\web\HttpException;
use common\components\helpers\General;
use common\models\Company;
use common\models\CompanyCategory;
use common\models\Deal;
use common\models\Tag;
use common\models\Event;
use common\models\Customer;
use common\models\Follow;
use common\models\Module;
use common\models\ModuleInstalled;
use common\models\Principal;
use common\models\FeatureSubscription;
use common\models\FeatureSubscriptionCompany;
use common\models\FeatureSubscriptionCompanyFree;
use common\models\FeatureSubscriptionCompanyFreeProgram;
use common\models\FeatureSubscriptionDetail;
use common\models\FeatureSubscriptionCompanyDetail;
use common\models\Appointment;
use common\models\HardwareCompany;
use common\models\Checkin;
use common\models\User;
use common\models\City;
use common\models\Region;
use common\models\Country;
use common\models\Mall;
use common\models\MallMerchant;
use common\models\LoyaltyPointMerchant;
use common\models\LoyaltyPointReward;
use common\models\LoyaltyPointRate;
use common\models\ChangeRequest;
use common\models\NotificationCompany;
use common\models\SystemMessage;
use common\models\SegmentCompany;
use common\models\AuditReport;
use common\components\helpers\Identity;
use common\models\FloorPlanMall;
use common\models\FloorPlanUnit;
use common\models\FloorPlanMallMerchant;
use common\models\InitialRegisterSetup;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class BusinessController extends BaseController {

    public $enableCsrfValidation = false;
    public $mall_code = '';

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    /**
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex() {
        $this->setRememberUrl();
        $date = '1970-01-01 - ' . date('Y-m-d 23:59:59');
        if(Yii::$app->request->get('daterange'))
            $date = Yii::$app->request->get('daterange');
        $totalBusiness = Company::find()->getTotalBusiness();

        $relation = ['subscriptionCompany.featureSubscription', 'category', 'user', 'mallcategory', 'marchant', 'companyTag.tag'];
        $totalNewBusiness = Company::find()->getTotalNewBusinessToday();
        /**
         * Business
         */
        $thisMonthBusiness = Company::find()->thisMonth->count();
        $lastMonthBusiness = Company::find()->lastMonth->count();
        $businessPercentage = (((int) $thisMonthBusiness - (int) $lastMonthBusiness) / 100);

        /**
         * exclusive
         */
        $totalExclusive = Company::find()->exclusive->businessNoSearch->count();
        $thisEMonth = Company::find()->exclusive->thisMonth->count();
        $lastEMonth = Company::find()->exclusive->lastMonth->count();
        $exclusivePercentage = (((int) $thisEMonth - (int) $lastEMonth) / 100);

        $business = Company::find()->with($relation)->getBusiness($date);
        $dataProvider = new ActiveDataProvider([
            'query' => $business,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        $tagData = [];
        $taglist = Tag::find()->getTagList();
        foreach ($taglist as $tagg => $tagv) {
            $tagData[$tagg] = $tagv;
        }

        $totalHq = Company::find()->totalHq;
        $totalIndependent = Company::find()->totalIndependent;
        $totalJoined = Company::find()->totalJoined;
        $totalActivePromo = Company::find()->getTotalActivePromo($date);
        $totalNewPromo = Company::find()->getTotalNewPromo($date);
        $totalBrandNewPromo = Company::find()->getTotalBrandNewPromo($date);
        $percentageNewPromo = 0;
        if($totalBrandNewPromo > $totalNewPromo)
            $percentageNewPromo = abs($totalBrandNewPromo / $totalNewPromo);
        $totalJoined = Company::find()->totalJoined;

        return $this->render('index', [
            'totalBusiness' => $totalBusiness,
            'businessPercentage' => $businessPercentage,
            'totalNewBusiness' => $totalNewBusiness,
            'totalExclusive' => $totalExclusive,
            'exclusivePercentage' => $exclusivePercentage,
            'dataProvider' => $dataProvider,
            'taglist' => Json::encode($tagData),
            'totalHq' => $totalHq,
            'totalIndependent' => $totalIndependent,
            'totalJoined' => $totalJoined,
            'totalActivePromo' => $totalActivePromo,
            'totalNewPromo' => $totalNewPromo,
            'totalBrandNewPromo' => $totalBrandNewPromo,
            'percentageNewPromo' => $percentageNewPromo,
            'totalJoined' => $totalJoined,
        ]);
    }

    public function actionJoined($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'joined';
        $model->com_joined = 1;
        $model->com_joined_datetime = time();
        $model->com_joined_by = Yii::$app->user->id;
        if($model->save())
            $this->setMessage('save', 'success', 'Merchant joined successfully!');
        else
            $this->setMessage('save', 'error', 'Merchant joined failed!<br/>Please check again');
        return $this->redirect([$this->getRememberUrl()]);
    }

    public function company() {
        if (!($_POST)) {
            \Yii::$app->session->set('company', '');
        }
        $company_ses = \Yii::$app->session->get('company');
        $company = unserialize($company_ses);
        if (empty($company_ses)) {
            $company = new Company();
            $company->setScenario('signup');
            $company->idTemp = time();
            \Yii::$app->session->set('company', serialize($company));
        }

        return $company;
    }

    // tagging
    public function actionTaglist() {
        if (\Yii::$app->request->isAjax) {
            $response = [];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $tag_id = \Yii::$app->request->post('tag_id');
            $category = \Yii::$app->request->post('category');
            // if (!empty($tag_id))
            // {
//            $response = (new yii\db\Query())
//                    ->select('a.tag_id, a.tag_name')
//                    ->from('tbl_tag a')
//                    ->innerJoin('tbl_tag_category z', 'z.tac_tag_id = a.tag_id')
//                    ->innerJoin('tbl_tag y', 'z.tac_tag_parent_id = y.tag_id')
//                    ->innerJoin('tbl_company_category x', 'x.com_category_id = y.tag_com_category_id')
//                    ->where('x.com_category_id = :category', [':category' => $category])
//                    ->all();
            // }
            $response = Tag::find()->getCategoryTagList($category);
            return $response;
        }
    }

    public function actionSettag() {
        if (\Yii::$app->request->isAjax) {
            $response = ['success' => 0];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $tag_id = \Yii::$app->request->post('tag_id');
            if (!empty($tag_id)) {
                $value = \common\models\Tag::findOne($tag_id)->tag_name;
                $response = ['success' => 1, 'data' => $tag_id, 'value' => $value];
            } else {
                $response = ['success' => 0];
            }

            return $response;
        }
    }

    public function actionTag() {
        $category = isset($_GET['category']) ? $_GET['category'] : 0;
        $com_id = isset($_GET['com_id']) ? $_GET['com_id'] : 0;
        $model = Tag::find()->getTag();
        $chosen = Tag::find()->getChoosenTag();
        $arr = [
            'com_id' => $com_id,
            'data' => $model,
            'chosen' => $chosen
        ];
        return Json::encode($arr);
    }

    public function actionBeforecreate() {
        return $this->render('before');
    }

    /**
     *  function for create business when log in as admin
     */
    public function actionCreate($reg = null) {
        $model = new User();
        $model->scenario = 'signup';
        $model->usr_password = md5('123456');
        $model->usr_type_id = 2;
        $model->usr_approved = 0;

        $company = $this->company();

        // ajax validation
        if (Yii::$app->request->isAjax && $company->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($company);
        }

        if ($company->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            $model->usr_email = $company->com_email;
            try {
                if ($model->save()) {
                    $company->com_usr_id = $model->usr_id;
                    $company->com_email = $model->usr_email;
                    if(!empty($company->com_sales_order))
                        $company->com_sales_order = strtotime($company->com_sales_order);

                    if ($company->save()) {
                        $audit = AuditReport::setAuditReport('create business : ' . $company->com_name, Yii::$app->user->id, Company::className(), $company->com_id);
                        if ($audit->save()) {
                            \Yii::$app->session->set('company', '');
                            $com_id = $company->com_id;
                            $company->setTag();
                            $fsc = new FeatureSubscriptionCompany();
                            $this->assignFcs($fsc, $com_id, $company, $company->fes_id);
                            if ($fsc->save()) {
                                $this->assignModule($com_id, $company);
                                $this->assignEmail($com_id, $company);
                                $transaction->commit();
                                $this->setMessage('save','success', 'Business created successfully!');
                                return $this->redirect([$this->getRememberUrl()]);
                            } else {
                                $transaction->rollback();
                                $this->setMessage('save','error', 'Something wrong while subscription business. Please try again!');
                                return $this->redirect(['index']);
                            }
                        }else{
                            $transaction->rollback();
                            $this->setMessage('save','error', 'Something wrong while create business. Please try again!');
                            return $this->redirect(['index']);
                        }
                    }else{
                        $transaction->rollback();
                        $this->setMessage('save','error', 'Something wrong while create business. Please try again!');
                        return $this->redirect(['index']);
                    }
                } else {
                    $transaction->rollback();
                    $this->setMessage('save','error', 'Something wrong while create member. Please try again!');
                    return $this->redirect(['index']);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                throw $e;
            }
        }

        $company->com_registered_to = strtoupper($reg);
        return $this->render('form', [
            'model' => $company,
        ]);
    }

    public function actionCreatetm() {
        $model = new Company();
        $model->setScenario('signup');

        // ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // step 1 save user data
                $user = new User();
                $user->setScenario('signup');
                $user->usr_email = $model->com_email;
                $user->usr_password = '123456';
                $user->usr_type_id = User::COMPANY;
                $user->usr_approved = 0;

                if ($user->save()) {
                    // step 2 save company data
                    $model->com_keywords = $model->com_name;
                    $model->com_longitude = $_POST['long'];
                    $model->com_latitude = $_POST['lat'];
                    $model->com_usr_id = $user->usr_id;
                    $model->com_registered_to = User::REGISTERED_TO_TM;
                    $model->com_currency = 'MYR';

                    if ($model->save()) {
                        $audit = AuditReport::setAuditReport('create TM business : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id);
                        if ($audit->save()) {
                            // step 3 save subscription data
                            $setup = new InitialRegisterSetup();
                            $subscription = $setup->setSubscriptionPackageTM($model);
                            if ($subscription->save()) {

                                // set depedency data
                                $setup->setPaymentMethod($model);
                                $setup->setTableArea($model);
                                $setup->setModule($model);
                                $setup->setHardwareCompany($model);

                                // send welcome Email
                                $setup->welcomeMessageTM($model);

                                $transaction->commit();

                                $message = 'Your account has been registered. Please check your email to continue setup your account.<br/><br/>'
                                        . 'If you don\'t receive an activation link in 24 hours, please contact us at support@ebizu.com and attach the email that you have registered as referral.';

                                // untuk paket free trial, langsung kirim invoice
                                if ($model->free_trial == 1) {
                                    $setup->sendInvoiceTM($user);
                                    $message = 'Your account has been registered.';
                                }

                                $this->setMessage('save', 'success', $message);
                                return $this->redirect(['index']);
                            } else {
                                /*
                                 * Karena dalam form company tidak ada field untuk subsription
                                 * maka tampilkan error message nya dalam flash message
                                 */
                                $this->setMessage('save', 'error', 'Subscription setup : <br/>' . General::extactErrorModel($subscription->getErrors()));
                                $transaction->rollback();
                                return $this->redirect(['createtm']);
                            }
                        }else{
                            $transaction->rollback();
                        }
                    } else {
                        // error will be appearing on the form
                        $transaction->rollback();
                    }
                } else {
                    /*
                     * Karena dalam form company tidak ada field untuk subsription
                     * maka tampilkan error message nya dalam flash message
                     */
                    $this->setMessage('save', 'error', 'User setup : <br/>' . General::extactErrorModel($user->getErrors()));
                    $transaction->rollback();
                    return $this->redirect(['createtm']);
                }
            } catch (Exception $e) {
                $transaction->rollback();
                $message = 'Something wrong while inserting company data, please <a href="' . Yii::$app->urlManager->createAbsoluteUrl(['auth/signup-ebc']) . '">click here</a> to try again. <br/>Error details : <br/>' . $e->getMessage();
                $this->setMessage('save', 'error', 'General setup : <br/>' . $message);
                return $this->redirect(['createtm']);
            }
        }

        return $this->render('telkom/form', [
            'model' => $model,
        ]);
    }

    public function actionSwitchPackage() {
        $model = FeatureSubscription::find()->getFesCodeFesName();
        echo json_encode(['data' => $model->all()]);
    }

    public function actionRegister() {
        $fes_code = isset($_GET['reg']) ? $_GET['reg'] : 'EBC';
        return FeatureSubscription::packageList($fes_code);
    }

    public function actionList($q = null) {
        $query = Company::getListEmailCompany($q);
        $return = [];
        foreach ($query as $row) {
            $return[]['value'] = $row['com_id'] . ': ' . $row['com_name'];
        }
        return Json::encode($return);
    }

    public function actionUserlist($q = null) {
        $query = Company::find()->getUserIdCompanyName();
        $return = [];
        foreach ($query->all() as $row) {
            $return[]['value'] = $row['com_usr_id'] . ': ' . $row['com_name'];
        }
        return Json::encode($return);
    }

    public function actionSelect() {
        $query = Company::find()->getSelected();
        $answer = [];
        $answer[] = ['id' => 0, 'text' => 'Ebizu'];
        foreach ($query->all() as $row) {
            $answer[] = ['id' => $row['com_id'], 'text' => $row['com_name']];
        }
        $res = [];
        $res['total'] = count($answer);
        $res['results'] = $answer;
        return Json::encode($res);
    }

    public function actionSelect2Partner($search = null, $id = null) {
        $model = Company::find()->searchExistingMerchantPartner();
        $data = $model->all();
        $out['results'] = array_values($data);
        if(!empty($_GET['id'])){
            $model = Company::find()->searchExistingMerchantPartner();
            $data = $model->one();
            $out['results'] = $data;
        }

         
         foreach ($data as $val => $key) {
             $out[] = ['id'=>$key['id'],'text'=>$key['text']];
         }
         echo json_encode($out,true);
    }

    public function actionSelect2($search = null, $id = null) {
        $model = Company::find()->searchExistingMerchant();
        $data = $model->all();
        $out['results'] = array_values($data);
        if(!empty($_GET['id'])){
            $model = Company::find()->searchExistingMerchant();
            $data = $model->one();
            $out['results'] = $data;
        }

         
         foreach ($data as $val => $key) {
             $out[] = ['id'=>$key['id'],'text'=>$key['text']];
         }
         echo json_encode($out,true);
    }

    public function actionMiniselect() {
        $res = [];
        $ret = [];
        $out = [];
        if ((isset($_GET['term']) && strlen($_GET['term']) > 0) || (isset($_GET['id']) && is_numeric($_GET['id']))) {
            if (isset($_GET['term'])) {
                $where = " com_name LIKE '%" . $_GET['term'] . "%' AND com_hq_id = 0 AND com_type = 1 ";
            } elseif (isset($_GET['id'])) {
                $where = " com_id = " . $_GET['id'] . " ";
            }
            $limit = intval($_GET['page_limit']);
            $query = "
				SELECT com_id, com_name
				FROM tbl_company
				WHERE " . $where . "
				ORDER BY com_name
				LIMIT " . $limit;
            $data = Yii::$app->db->createCommand($query)->queryAll();
            
//            $data = Company::find()->getMiniSelect();
            foreach ($data as $row) {
                $res['id'] = $row['com_id'];
                $res['text'] = $row['com_name'];
                array_push($ret, $res);
            }
        } else {
            $res['id'] = 0;
            $res['text'] = 'Ebizu';
            array_push($ret, $res);
        }
        if (isset($_GET['id'])) {
            $query = "
				SELECT com_id, com_name
				FROM tbl_company
				WHERE com_id = " . $_GET['id'];
            $data = Yii::$app->db->createCommand($query)->queryAll();
            $res['id'] = $data[0]['com_id'];
            $res['text'] = $data[0]['com_name'];
            $out = $res;
        } else {
            $out['results'] = $ret;
        }
        return Json::encode($out);
    }

    public function actionHardwareselect() {
        $res = [];
        $ret = [];
        $out = [];
        if ((isset($_GET['term']) && strlen($_GET['term']) > 0) || (isset($_GET['id']) && is_numeric($_GET['id']))) {
            if (isset($_GET['term'])) {
                $where = " com_name LIKE '%" . $_GET['term'] . "%' AND com_hq_id = 0 AND com_type = 0 ";
            } elseif (isset($_GET['id'])) {
                $where = " com_id = " . $_GET['id'] . " ";
            }
            $limit = intval($_GET['page_limit']);
            $query = "
				SELECT com_id, com_name
				FROM tbl_company
				WHERE " . $where . "
				ORDER BY com_name
				LIMIT " . $limit;
            $data = Yii::$app->db->createCommand($query)->queryAll();
            foreach ($data as $row) {
                $res['id'] = $row['com_id'];
                $res['text'] = $row['com_name'];
                array_push($ret, $res);
            }
        } else {
            $res['id'] = 0;
            $res['text'] = 'Ebizu';
            array_push($ret, $res);
        }
        if (isset($_GET['id'])) {
            $query = "
				SELECT com_id, com_name
				FROM tbl_company
				WHERE com_id = " . $_GET['id'];
            $data = Yii::$app->db->createCommand($query)->queryAll();
            $res['id'] = $data[0]['com_id'];
            $res['text'] = $data[0]['com_name'];
            $out = $res;
        } else {
            $out['results'] = $ret;
        }
        return Json::encode($out);
    }

    public function actionCount() {
        $type = (isset($type) ? 1 : 0);
        $model = Company::find()->countCompany($type);
        return $model->count();
    }

    public function actionLocation($id) {
        $model = Company::find()->select(['com_id','com_name'])->where("com_hq_id = '$id' OR com_id = '$id'")->orderBy('com_name');
        $html = '';
        foreach ($model->all() as $row) {
            $html .= '<input type="checkbox" name="business_location[' . $row['com_id'] . ']" /> ' . $row['com_name'] . '<br/>';
        }
        return $html;
    }

    // ========== RESET PASSWORD =================

    public function actionReset($id) {
        $usr_id = Company::findOne($id)->com_usr_id;
        $model = User::findOne($usr_id);
        if (empty($model)) {
            $this->setMessage('save', 'error', 'No match between user id or business id!');
            return $this->redirect(['business/index']);
        }
        $model->scenario = 'changepassword';
        if ($model->load(Yii::$app->request->post())) {
            $model->changepassword = true;
            if ($model->save()) {
                // send email
                $to = $model->usr_email;
                $params = [
                    'email' => $to,
                    'password' => $model->new_password_repeat
                ];
                $body = Yii::$app->AdminMail
                        ->backend($to, $params)
                        ->registerEmployee()
                        ->send()
                        ->view();

                $this->setMessage('save', 'success', 'Reset business password successfully!');
                $audit = AuditReport::setAuditReport('reset business password : ' . $model->usr_email, Yii::$app->user->id, User::className(), $model->usr_id);
                if ($audit->save())
                    return $this->redirect(['reset', 'id' => $id]);
            }
        }
        $business = $this->findModel($id)->com_name;
        $model->usr_password = '';
        return $this->render('reset', [
            'model' => $model,
            'business' => $business
        ]);
    }

    // =========== CHANGE REQUEST ==============

    public function actionChangeindex() {
        return $this->render('request');
    }

    public function actionChangelist() {
        $filter = Json::decode($_GET['filter']);
        $type = (isset($type) ? 1 : 0);
        $page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
        $itemPerPage = (!isset($_GET['itemPerPage'])) ? 10 : $_GET['itemPerPage'];
        $count = 0;
        $connection = Yii::$app->db;
        $query = "
			SELECT a.chg_id, a.chg_usr_id, a.chg_username, a.chg_usr_email, a.chg_com_name,
				a.chg_com_business_name, a.chg_currency, a.chg_request_time
			FROM tbl_change_request a
			LEFT JOIN tbl_company b ON b.com_usr_id = a.chg_usr_id
			LEFT JOIN tbl_user c ON c.usr_id = a.chg_usr_id
			WHERE c.usr_type_id = 2
				AND a.chg_moderated_time = 0
				AND a.chg_moderated_by = 0
		";
        if (!empty($_GET['filter'])) {
            foreach ($filter as $fl => $v) {
                $query .= "AND " . $fl . " LIKE '%" . $v . "%' ";
            }
        }
        $query .= 'ORDER BY a.chg_request_time DESC ';
        $queryTotal = $query;
        $offset = ($page * $itemPerPage) - $itemPerPage;
        $query = $query . "
			LIMIT " . $itemPerPage . " OFFSET " . $offset;

        $total = $connection->createCommand($queryTotal)->queryAll();
        $model = $connection->createCommand($query)->queryAll();

        if (!empty($_GET['filter'])) {
            $count = count($total);
        } else {
            $query = "
				SELECT a.chg_id, a.chg_usr_id, a.chg_username, a.chg_usr_email, a.chg_com_name,
					a.chg_com_business_name, a.chg_currency, a.chg_request_time
				FROM tbl_change_request a
				LEFT JOIN tbl_company b ON b.com_usr_id = a.chg_usr_id
				LEFT JOIN tbl_user c ON c.usr_id = a.chg_usr_id
				WHERE c.usr_type_id = 2
					AND a.chg_moderated_time = 0
					AND a.chg_moderated_by = 0
			";
            $count = ChangeRequest::findBySql($query)->count();
        }
        $result = [];
        $result['totalData'] = $count;
        $result['data'] = $model;
        return Json::encode($result);
    }

    public function actionChangeapproval() {
        $data = Json::decode(file_get_contents('php://input'));
        $model = ChangeRequest::findOne($data['chg_id']);

        // send email
        $user = User::findOne($model->chg_usr_id);
        $business = Company::find()->where(['com_usr_id' => $user->usr_id])->one()->com_name;
        $email = $user->usr_email;
        $parsers = [];
        $name = 'change_request_approval';
        $parsers[] = array('[name]', $business);
        $message = new SystemMessage;
        $message->parser(42, $name, $email, $parsers);

        // save to db
        $model->chg_moderated_time = time();
        $model->chg_moderated_by = Yii::$app->user->id;
        $model->save(false);
    }

    public function actionChangerequest($id) {
        $model = ChangeRequest::findOne($id);
        return Json::encode($model);
    }

    public function actionRejectchange() {
        $data = Json::decode(file_get_contents('php://input'));
        $model = ChangeRequest::findOne($data['chg_id']);

        $content = '<ol>';
        if (isset($data['chg_username'])) {
            $content .= '
				<li>Name has been taken. (so they shall use another way to write the name e.g. thegrill)<br/>
				Name contains inappropriate words.</li>
			';
        }
        if (isset($data['chg_com_name'])) {
            $content .= '
				<li>Name has been taken. (so they shall use another way to write the name e.g. The Grill @ Bangsar South)<br/>
				Name contains inappropriate words.</li>
			';
        }
        if (isset($data['chg_usr_email'])) {
            $content .= '
				<li>Email has been taken.<br/>
				Email is not valid or it might be written incorrectly.</li>
			';
        }
        if (isset($data['chg_com_phone'])) {
            $content .= '
				<li>The contact number is unavailable.</li>
			';
        }
        if (isset($data['chg_com_reg_num'])) {
            $content .= '
				<li>The registration number is invalid. Please re-enter.</li>
			';
        }
        if (isset($data['chg_com_address'])) {
            $content .= '
				<li>The address is not found in our system. Please re-enter.</li>
			';
        }
        if (isset($data['chg_com_website'])) {
            $content .= '
				<li>Requested URL is not valid.</li>
			';
        }
        $content .= '</ol>';

        // send email
        $user = User::findOne($model->chg_usr_id);
        $business = Company::find()->where(['com_usr_id' => $model->chg_usr_id])->one()->com_name;
        $email = $user->usr_email;
        $parsers = [];
        $name = 'change_request_reject';
        $parsers[] = array('[name]', $business);
        $parsers[] = array('[message]', $content);
        $message = new SystemMessage;
        $message->parser(43, $name, $email, $parsers);
    }

    public function actionNew() {
        $filter = Json::decode($_GET['filter']);
        $type = (isset($type) ? 1 : 0);
        $page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
        $itemPerPage = (!isset($_GET['itemPerPage'])) ? 10 : $_GET['itemPerPage'];
        $count = 0;
        $connection = Yii::$app->db;
        $query = "
			SELECT com_id, com_name, com_email, com_photo, com_created_date, com_registered_to
			FROM tbl_company
			WHERE com_hq_id = 0
				AND com_status = 0
				AND com_type = " . $type . "
		";
        if (!empty($_GET['filter'])) {
            foreach ($filter as $fl => $v) {
                if ($fl == 'com_registered_to') {
                    if ($v == 0)
                        $query .= "AND com_registered_to = 'EBC' ";
                    else
                        $query .= "AND com_registered_to = 'TM' ";
                } else {
                    $query .= "AND " . $fl . " LIKE '%" . $v . "%' ";
                }
            }
        }
        $query .= "ORDER BY com_created_date DESC ";
        $queryTotal = $query;
        $offset = ($page * $itemPerPage) - $itemPerPage;
        $query = $query . "
			LIMIT " . $itemPerPage . " OFFSET " . $offset;

        $total = $connection->createCommand($queryTotal)->queryAll();
        $model = $connection->createCommand($query)->queryAll();

        if (!empty($_GET['filter'])) {
            $count = count($total);
        } else {
            $count = Company::find()->where(['com_hq_id' => 0, 'com_status' => 0, 'com_type' => $type])->count();
        }
        $result = [];
        $result['totalData'] = $count;
        $result['data'] = $model;
        return Json::encode($result);
    }

    public function actionUncomplete() {
        $filter = Json::decode($_GET['filter']);
        $page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
        $itemPerPage = (!isset($_GET['itemPerPage'])) ? 10 : $_GET['itemPerPage'];
        $count = 0;
        $connection = Yii::$app->db;
        $query = "
			SELECT a.com_id, a.com_name, a.com_email, a.com_photo, a.com_created_date, a.com_registered_to,
				CONCAT(b.cit_name, ', ', c.reg_name, ', ', d.cny_name) AS city
			FROM tbl_company a
			LEFT JOIN tbl_city b ON b.cit_id = a.com_city_id
			LEFT JOIN tbl_region c ON c.reg_id = a.com_region_id
			LEFT JOIN tbl_country d ON d.cny_id = a.com_country_id
			WHERE a.com_hq_id = 0
				AND a.com_type = 0
				AND (a.com_subcategory_id = 0
					OR a.com_city_id = 0)
		";
        if (!empty($_GET['filter'])) {
            foreach ($filter as $fl => $v) {
                $query .= "AND " . $fl . " LIKE '%" . $v . "%' ";
            }
        }
        $query .= "ORDER BY a.com_created_date DESC ";
        $queryTotal = $query;
        $offset = ($page * $itemPerPage) - $itemPerPage;
        $query = $query . "
			LIMIT " . $itemPerPage . " OFFSET " . $offset;

        $total = $connection->createCommand($queryTotal)->queryAll();
        $model = $connection->createCommand($query)->queryAll();

        if (!empty($_GET['filter'])) {
            $count = count($total);
        } else {
            $query = "
				SELECT a.com_id
				FROM tbl_company a
				LEFT JOIN tbl_city b ON b.cit_id = a.com_city_id
				LEFT JOIN tbl_region c ON c.reg_id = a.com_region_id
				LEFT JOIN tbl_country d ON d.cny_id = a.com_country_id
				WHERE a.com_hq_id = 0
					AND a.com_type = 0
					AND (a.com_subcategory_id = 0
						OR a.com_city_id = 0)
			";
            $count = Company::findBySql($query)->count();
        }
        $result = [];
        $result['totalData'] = $count;
        $result['data'] = $model;
        return Json::encode($result);
    }

    public function actionDetail($id) {
        $connection = Yii::$app->db;
        $query = "
			SELECT a.com_id, a.com_name, a.com_description, a.com_subcategory_id, b.com_category, a.com_city,
				CONCAT(c.cit_name, ', ', d.reg_name, ', ', e.cny_name) AS city, a.com_address, a.com_postcode, a.com_phone,
				a.com_fax, a.com_email, a.com_website, a.com_latitude, a.com_longitude, a.com_size, a.com_registered_to,
				a.com_nbrs_employees, a.com_photo, a.com_banner_photo, a.com_status, a.com_moderated,
				a.com_created_date, a.com_fb, a.com_twitter, a.com_language, a.com_currency, a.com_timezone,
				a.com_reg_num, a.com_agent_code, a.com_business_name, a.com_point, a.com_snapearn, a.com_snapearn_checkin,
				f.usr_approved, f.usr_approved_datetime, (
					SELECT usr_name FROM adm_user WHERE usr_id = f.usr_approved_admin_id
				) AS admin_approved,
				f.usr_rejected, f.usr_rejected_datetime, (
					SELECT usr_name FROM adm_user WHERE usr_id = f.usr_rejected_admin_id
				) AS admin_rejected
			FROM tbl_company a
			LEFT JOIN tbl_company_category b ON b.com_category_id = a.com_subcategory_id
			LEFT JOIN tbl_city c ON c.cit_id = a.com_city_id
			LEFT JOIN tbl_region d ON d.reg_id = a.com_region_id
			LEFT JOIN tbl_country e ON e.cny_id = a.com_country_id
			LEFT JOIN tbl_user f ON f.usr_id = a.com_usr_id
			WHERE a.com_id = " . $id;
        $query = $connection->createCommand($query)->queryAll();
        return Json::encode($query);
    }

    public function actionUpdatemap($id) {
        $data = Json::decode(file_get_contents('php://input'));
        $lat = $data['lat'];
        $lng = $data['lng'];
        $model = Company::findOne($id);
        $model->scenario = 'map';
        $model->com_latitude = $lat;
        $model->com_longitude = $lng;
        $model->save();
        return 'Business map has been updated successfully!';
    }

    public function actionUpdatephoto($id) {
        $data = Json::decode(file_get_contents('php://input'));
        $photo = $data['photo'];

        // move from aws temp to path
        require_once Yii::$app->params['libPath'] . 'aws' . DIRECTORY_SEPARATOR . 'aws-autoloader.php';
        $client = S3Client::factory(array(
                    'key' => Yii::$app->params['s3key'],
                    'secret' => Yii::$app->params['s3secret'],
                    'region' => Yii::$app->params['s3region']
        ));

        $bucket = Yii::$app->params['s3bucket'];
        $path = 'images/media/web/business/';
        $result = $client->copyObject(array(
            'Bucket' => $bucket,
            'CopySource' => $bucket . '/' . $path . "temp/" . $photo,
            'Key' => $path . $photo,
            'ACL' => 'public-read',
        ));

        $client->waitUntilObjectExists(array(
            'Bucket' => $bucket,
            'Key' => $path . $photo
        ));

        $result = $client->copyObject(array(
            'Bucket' => $bucket,
            'CopySource' => $bucket . '/' . $path . "temp/s_" . $photo,
            'Key' => $path . "s_" . $photo,
            'ACL' => 'public-read',
        ));

        $client->waitUntilObjectExists(array(
            'Bucket' => $bucket,
            'Key' => $path . "s_" . $photo
        ));

        $result = $client->deleteObjects(array(
            'Bucket' => $bucket,
            'Objects' => array(
                array('Key' => $path . "temp/temp_" . $photo),
                array('Key' => $path . "temp/" . $photo),
                array('Key' => $path . "temp/s_" . $photo),
            ),
            'ContentMD5' => true,
        ));

        // update to db
        $model = Company::findOne($id);
        $model->scenario = 'photo';
        $model->com_photo = $photo;
        $model->save();
        return 'Business logo has been updated successfully!';
    }

    public function actionApproved($id, $from = 0) {
        $results = [];

        // get user id
        $usr_id = Company::findOne($id)->com_usr_id;
        $now = time();
        $model = User::findOne($usr_id);
        $model->scenario = 'approved';

        // get email and key activation
        $key = md5(rand());

        // update to user
        $model->usr_approved = 1;
        $model->usr_approved_admin_id = Yii::$app->user->id;
        $model->usr_approved_datetime = $now;
        $model->usr_approved_url_activation = $key;
        $model->usr_approved_confirm = 0;
        $model->usr_rejected = 0;
        $model->usr_rejected_admin_id = 0;
        $model->usr_rejected_datetime = 0;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                $audit = AuditReport::setAuditReport('approval business : ' . $model->usr_email, Yii::$app->user->id, User::className(), $model->usr_id);
                if ($audit->save()) {
                    // update status business
                    $business = Company::findOne($id);
                    $business->scenario = 'change_status';
                    $email = $business->com_email;
                    $com_name = $business->com_name;
                    $business->com_status = 1;
                    $business->save();

                    if ($business->com_prc_id > 1) {
                        $this->oldActivationMethod($business, $key);
                    } else {
                        $setup = new InitialRegisterSetup();
                        if ($business->com_registered_to == User::REGISTERED_TO_TM) {
                            $setup->activationTM($business);
                        } else if ($business->com_registered_to == User::REGISTERED_TO_MGR || $business->com_registered_to == User::REGISTERED_TO_POS) {
                            $setup->activationManager($business);
                        } else {
                            $this->oldActivationMethod($business, $key);
                        }
                    }

                    // callback
                    $results['approved'] = 1;
                    $results['admin'] = Yii::$app->user->identity->username;
                    $results['datetime'] = $now;
                    $transaction->commit();

                    return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('dashboard/index'));
                }
            }
        } catch (Exception $e) {
            $transaction->rollback();
            $return = ['result' => 0, 'data' => $data, 'msg' => 'Invalid update stock'];
            throw $e;
        }
    }

    public function actionActivate($key = null)
    {
        $url = \common\components\helpers\URL::getFrontendURL() . '/activation/register-merchant?activation=' . $key;
        return $this->redirect($url);
    }

    private function oldActivationMethod($business, $key) {
        // sending email
        
        $parsers = [];
        $msg_id = 18;
        if ($business->com_registered_to == 'EBC' || $business->com_registered_to == 'BSC') {
            $name = 'business_activation';
            $url = $this->getUrlActivation('EBC') . 'activation/register-merchant?activation=' . $key;
            $link = '<a href="' . $url . '">Click here to activate</a>';
        } elseif ($business->com_prc_id > 1) { // prinsipal
            $url = $this->getUrlActivation('TM') . 'activation/register-master-account?activation=' . $key;
            $link = '<a href="' . $url . '">Click here to activate</a>';
        } elseif ($business->com_registered_to == User::REGISTERED_TO_RHB) {
            $url = $this->getUrlActivation('RHB') . 'activation/step1?activation=' . $key;
            $link = '<a href="' . $url . '">Click here to activate</a>';
            $name = 'pos_activation';
            $msg_id = 47;
        }

        $parsers[] = array('[id]', $business->com_id);
        $parsers[] = array('[link]', $link);
        $parsers[] = array('[name]', $business->com_name);
        $parsers[] = array('[register_to]', $business->com_registered_to);
        $message = new SystemMessage;
        $message->parser($msg_id, $name, $business->com_email, $parsers);
    }

    /*
     * ulah di delete nyak awas siah ku aing di gantung siah
     *  by mamang
     */

    public function getUrlActivation($registerTo = 'EBC') {
        $host = Yii::$app->urlManager->hostInfo;
        $arrHost = ['http://local.ebc2adminproduction.com' => 'http://local.ebc2production.com'];
        //echo $host;die;
        if ($registerTo == 'EBC') {
            if ($host == 'http://local.ebc2adminproduction.com') {//local
                $returnUrl = 'http://local.ebc1.com/';
            } else if ($host == 'http://ebizu.local') {
                $returnUrl = 'http://ebc.local/';
            } else if ($host == 'https://admingitdev.ebizu.com') {//development
                $returnUrl = 'https://ebcgitdev.ebizu.com/';
            } else if ($host == 'http://mallstaging.ebizu.com') {//staging
                $returnUrl = 'https://ebc2staging.ebizu.com/';
            } else if ($host == 'http://mall.ebizu.com') {//production
                $returnUrl = 'https://biz.ebizu.com/';
            } else if ($host == 'http://local.mall2production.com') {//production
                $returnUrl = 'https://biz.ebizu.com/';
            } else {
                $returnUrl = 'https://biz.ebizu.com/';
            }
        } else if ($registerTo == User::REGISTERED_TO_TM || $registerTo == User::REGISTERED_TO_MGR || $registerTo == User::REGISTERED_TO_POS) {
            if ($host == 'http://local.ebc2adminproduction.com') {//local
                $returnUrl = 'http://local.ebc2production.com/';
            } else if ($host == 'http://local.mall2production.com') {//development
                $returnUrl = 'https://ebcgitdev.ebizu.com/';
            } else if ($host == 'https://admingitdev.ebizu.com') {//development
                $returnUrl = 'https://ebcgitdev.ebizu.com/';
            } else if ($host == 'http://mallstaging.ebizu.com') {//staging
                $returnUrl = 'https://ebc2staging.ebizu.com/';
            } else if ($host == 'http://mall.ebizu.com') {//production
                $returnUrl = 'https://biz.ebizu.com/';
            } else if ($host == 'http://local.mall2production.com') {//production
                $returnUrl = 'https://biz.ebizu.com/';
            } else {
                $returnUrl = 'https://biz.ebizu.com/';
            }
        } else if ($registerTo == User::REGISTERED_TO_RHB) {
            $returnUrl = 'https://rhb.ebizu.com/';
        }
        return $returnUrl;
    }

    public function actionRejected($id, $from = 0) {
        $results = [];

        // get user id
        $usr_id = Company::findOne($id)->com_usr_id;
        $time = time();
        $model = User::findOne($usr_id);
        $model->scenario = 'rejected';

        // update to user
        $model->usr_approved = 0;
        $model->usr_approved_admin_id = '';
        $model->usr_approved_datetime = 0;
        $model->usr_approved_url_activation = '';
        $model->usr_approved_confirm = 0;
        $model->usr_rejected = 1;
        $model->usr_rejected_admin_id = Yii::$app->user->id;
        $model->usr_rejected_datetime = $time;
        if ($model->save()) {
            // sending email
            $email = $model->usr_email;
            $parsers = [];
            $name = 'business_rejection';
            $parsers[] = array('[id]', $id);
            $message = new SystemMessage();
            $message->parser(19, $name, $email, $parsers);

            // callback
            $results['rejected'] = 1;
            $results['admin'] = Yii::$app->user->identity->username;
            $results['datetime'] = $time;
            if ($from == 0)
                return Json::encode($results);
        }
    }

    public function actionCategory() {
        $connection = Yii::$app->db;
        $query = "
			SELECT a.com_category_id, a.com_category, a.com_parent_category_id, a.com_icon,
				b.com_category_id AS parent_id, b.com_category AS parent
			FROM tbl_company_category a
			LEFT OUTER JOIN tbl_company_category b
				ON a.com_parent_category_id = b.com_category_id
			WHERE a.com_parent_category_id <> 0
		";
        $type = (int) (rtrim(isset($_GET['type']), '/'));
        if ($type == 1) {
            $query .= "AND b.com_category_type = 2 ";
        } else {
            $query .= "AND b.com_category_type = 1 ";
        }
        $query .= "
			ORDER BY b.com_category
		";
        $categories = $connection->createCommand($query)->queryAll();
        return Json::encode($categories);
    }

    public function actionCurrency() {
        $connection = Yii::$app->db;
        $query = "SELECT cur_symbol, cur_name FROM tbl_currency";
        $currency = $connection->createCommand($query)->queryAll();
        return Json::encode($currency);
    }

    public function actionDeletebusinessvoucherandphoto($id) {
        $model = Company::findOne($id);
        $photo = $model->com_photo;
        $user_id = $model->com_usr_id;

        require_once Yii::$app->params['libPath'] . 'aws' . DIRECTORY_SEPARATOR . 'aws-autoloader.php';
        $client = S3Client::factory(array(
                    'key' => Yii::$app->params['s3key'],
                    'secret' => Yii::$app->params['s3secret'],
                    'region' => Yii::$app->params['s3region']
        ));
        $bucket = Yii::$app->params['s3bucket'];
        $path = 'images/media/web/business/';
        $result = $client->deleteObjects(array(
            'Bucket' => $bucket,
            'Objects' => array(
                array('Key' => $path . "temp/temp_" . $photo),
                array('Key' => $path . "temp/" . $photo),
                array('Key' => $path . "temp/s_" . $photo),
                array('Key' => $path . $photo),
                array('Key' => $path . "s_" . $photo),
            ),
            'ContentMD5' => true,
        ));

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            // delete voucher redeemed :
            $query = "
				delete from tbl_voucher_redeemed where vor_vou_id IN
				(
					select distinct vor_vou_id from tbl_voucher
					where vou_com_id=" . $id . "
				)
        	";
            $connection->createCommand($query)->execute();

            // delete vouchere bought detail :
            $query = "
				delete tbl_voucher_bought_detail from tbl_voucher_bought_detail
				left join tbl_voucher_bought on vod_vob_id=vob_id
				left join `tbl_voucher` on vou_id=vob_vou_id
				where vou_com_id=" . $id;
            $connection->createCommand($query)->execute();

            // delete voucher bought :
            $query = "
				delete tbl_voucher_bought from tbl_voucher_bought
				left join `tbl_voucher` on vou_id=vob_vou_id
				where  vou_com_id=" . $id;
            $connection->createCommand($query)->execute();

            // delete voucher :
            $query = "
				delete from tbl_voucher
				where vou_com_id=" . $id . "
        	";
            $connection->createCommand($query)->execute();

            // delete company :
            $query = "delete from tbl_company where com_id=" . $id;
            $connection->createCommand($query)->execute();

            // delete user :
            $query = "delete from tbl_user where usr_id=" . $user_id;
            $connection->createCommand($query)->execute();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    // ============= PARTNER ================

    public function actionPartner() {
        $date = '1970-01-01 - ' . date('Y-m-d');
        $totalBusiness = Company::find()->getPartner($date);
        $business = Company::find()->getPartner($date);
        $dataProvider = new ActiveDataProvider([
            'query' => $business,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('partner/index', [
            'totalBusiness' => $totalBusiness,
            // 'businessPercentage' => $businessPercentage,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreatepartner() {
        $user = new User;
        $user->setScenario('signup');
        $user->usr_password = md5('123456');
        $user->usr_type_id = 2;
        $user->usr_approved = 0;
        if ($user->load(Yii::$app->request->post()) && $user->companyRegisterModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($user->save()) {
                    $company = new Company;
                    $company->com_longitude = $_POST['Company']['com_longitude'];
                    $company->com_latitude = $_POST['Company']['com_latitude'];
                    $company->com_usr_id = $user->usr_id;
                    $company->com_email = $user->usr_email;
                    $company->com_registered_to = User::REGISTERED_TO_EBC;
                    $company->com_hq_id = 0;
                    $company->com_usr_id = 0;
                    $company->com_type = 1;
                    $company->com_created_date = time();
                    if ($company->load(Yii::$app->request->post())) {
                        if ($company->save()) {
                            $audit = AuditReport::setAuditReport('create partner : ' . $company->com_name, Yii::$app->user->id, Company::className(), $company->com_id);
                            if ($audit->save()) {
                                $transaction->commit();
                                return $this->redirect(['partner']);
                            }else{
                                $transaction->rollback();
                            }
                        }else{
                            $transaction->rollback();
                        }
                    } else {
                        throw new HttpException(404, 'Cant insert to company');
                        $transaction->rollback();
                    }
                }else{
                    $transaction->rollback();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                throw $e;
            }
        }
        return $this->render('partner/form', [
                    'model' => $user,
        ]);
    }

    public function actionUpdatepartner($id) {
        $model = $this->findModel($id);
        $model->setScenario('update-profile');
        $model->com_description = \yii\helpers\Html::decode($model->com_description);
        if ($model->load(Yii::$app->request->post())) {
            $changed_attributes = $model->getChangedAttribute(['com_timezone']);
            if ($model->save()) {
                $audit = AuditReport::setAuditReport('update partner : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id, $changed_attributes);
                if ($audit->save())
                    return $this->redirect(['partner']);
            }
        }
        else {
            return $this->render('partner/update', [
                        'model' => $model
            ]);
        }
    }

    public function actionPartnerlist() {
        $filter = Json::decode($_GET['filter']);
        $type = (isset($type) ? 1 : 0);
        $page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
        $itemPerPage = (!isset($_GET['itemPerPage'])) ? 10 : $_GET['itemPerPage'];
        $count = 0;
        $connection = Yii::$app->db;
        $query = "
			SELECT a.com_id, a.com_name, a.com_description, b.com_category, a.com_city,
				c.cit_name, d.reg_name, e.cny_name, a.com_address, a.com_postcode, a.com_phone,
				a.com_fax, a.com_email, a.com_website, a.com_latitude, a.com_longitude, a.com_size,
				a.com_nbrs_employees, a.com_photo, a.com_banner_photo, a.com_status, a.com_moderated,
				a.com_created_date, a.com_fb, a.com_twitter, a.com_language, a.com_currency, a.com_timezone
			FROM tbl_company a
			LEFT JOIN tbl_company_category b ON b.com_category_id = a.com_subcategory_id
			LEFT JOIN tbl_city c ON c.cit_id = a.com_city_id
			LEFT JOIN tbl_region d ON d.reg_id = a.com_region_id
			LEFT JOIN tbl_country e ON e.cny_id = a.com_country_id
			WHERE a.com_hq_id = 0
				AND a.com_type = 1
				AND a.com_registered_to = 'EBC'
		";
        if (!empty($_GET['filter'])) {
            foreach ($filter as $fl => $v) {
                $query .= "AND " . $fl . " LIKE '%" . $v . "%' ";
            }
        }
        $queryTotal = $query;
        $offset = ($page * $itemPerPage) - $itemPerPage;
        $query = $query . "
			LIMIT " . $itemPerPage . " OFFSET " . $offset;

        $total = $connection->createCommand($queryTotal)->queryAll();
        $model = $connection->createCommand($query)->queryAll();

        if (!empty($_GET['filter'])) {
            $count = count($total);
        } else {
            $query = "
				SELECT a.com_id, a.com_name, a.com_description, b.com_category, a.com_city,
					c.cit_name, d.reg_name, e.cny_name, a.com_address, a.com_postcode, a.com_phone,
					a.com_fax, a.com_email, a.com_website, a.com_latitude, a.com_longitude, a.com_size,
					a.com_nbrs_employees, a.com_photo, a.com_banner_photo, a.com_status, a.com_moderated,
					a.com_created_date, a.com_fb, a.com_twitter, a.com_language, a.com_currency, a.com_timezone
				FROM tbl_company a
				LEFT JOIN tbl_company_category b ON b.com_category_id = a.com_subcategory_id
				LEFT JOIN tbl_city c ON c.cit_id = a.com_city_id
				LEFT JOIN tbl_region d ON d.reg_id = a.com_region_id
				LEFT JOIN tbl_country e ON e.cny_id = a.com_country_id
				WHERE a.com_hq_id = 0
					AND a.com_type = 1
					AND a.com_registered_to = 'EBC'
			";
            $count = Company::findBySql($query)->count();
        }
        $result = [];
        $result['totalData'] = $count;
        $result['data'] = $model;
        return Json::encode($result);
    }

    public function actionPartnerdetail($id) {
        $query = "
			SELECT a.com_id, a.com_name, a.com_description, b.com_category, a.com_city,
				c.cit_name, d.reg_name, e.cny_name, a.com_address, a.com_postcode, a.com_phone,
				a.com_fax, a.com_email, a.com_website, a.com_latitude, a.com_longitude, a.com_size,
				a.com_nbrs_employees, a.com_photo, a.com_banner_photo, a.com_status, a.com_moderated,
				a.com_created_date, a.com_fb, a.com_twitter, a.com_language, a.com_currency, a.com_timezone
			FROM tbl_company a
			LEFT JOIN tbl_company_category b ON b.com_category_id = a.com_subcategory_id
			LEFT JOIN tbl_city c ON c.cit_id = a.com_city_id
			LEFT JOIN tbl_region d ON d.reg_id = a.com_region_id
			LEFT JOIN tbl_country e ON e.cny_id = a.com_country_id
			WHERE a.com_id = " . $id;
        $connection = Yii::$app->db;
        $query = $connection->createCommand($query)->queryAll();
        return Json::encode($query);
    }

    public function actionDeletepartner($id) {
        $model = Company::findOne($id);
        $user = User::deleteAll('usr_id = :usr_id', [':usr_id' => $model->com_usr_id]);
        if ($model->delete()) {
            $audit = AuditReport::setAuditReport('delete partner : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id)->save();
            return $this->redirect(['business/partner']);
        }
    }

    // ============ TELKOM ================

    public function actionTelkom() {
        $totalBusiness = Company::find()
                ->where(['com_hq_id' => 0, 'com_type' => 0, 'com_registered_to' => 'TM'])
                ->count();
        $thisMonth = Company::find()->thisTelekomMonth->count();
        $lastMonth = Company::find()->lastTelekomMonth->count();
        $businessPercentage = (((int) $thisMonth - (int) $lastMonth) / 100);

        $totalBranches = Company::find()
                ->where("com_hq_id > 0 AND com_registered_to = 'TM'")
                ->count();
        $thisBranchMonth = Company::find()->thisTelekomBranchMonth->count();
        $lastBranchMonth = Company::find()->lastTelekomBranchMonth->count();
        $branchPercentage = (((int) $thisBranchMonth - (int) $lastBranchMonth) / 100);

        $query = "
			SELECT COUNT(*) AS totalNewBusiness FROM (
			SELECT b.usr_id, a.com_name, COUNT(a.com_id) as total
			FROM tbl_company a, tbl_user b
			WHERE a.com_usr_id = b.usr_id
				AND ((a.com_status = 1 AND a.com_hq_id = 0)
				OR ((a.com_status = 0 AND a.com_hq_id = 0)
					AND ((b.usr_approved = 0 AND a.com_hq_id = 0)
					OR (b.usr_rejected = 0 AND a.com_hq_id = 0))
				))
				AND a.com_registered_to = 'TM'
			GROUP BY a.com_name) x
		";
        $totalNewBusiness = Company::findBySql($query)->count();
        $query = "
			SELECT COUNT(*) AS thisNMonth FROM (
			SELECT a.com_id as total
			FROM tbl_company a, tbl_user b
			WHERE a.com_usr_id = b.usr_id
				AND FROM_UNIXTIME(com_created_date, '%Y') = YEAR(NOW())
				AND FROM_UNIXTIME(com_created_date, '%m') = MONTH(NOW())
				AND (a.com_status = 1
				OR (a.com_status = 0 AND (b.usr_approved = 0 OR b.usr_rejected = 0)))
				AND a.com_registered_to = 'TM'
			GROUP BY a.com_name) y
		";
        $thisNMonth = Company::findBySql($query)->count();
        $query = "
			SELECT COUNT(*) AS lastNMonth FROM (
			SELECT a.com_id as total
			FROM tbl_company a, tbl_user b
			WHERE a.com_usr_id = b.usr_id
				AND FROM_UNIXTIME(com_created_date, '%Y') = YEAR(NOW())
				AND (FROM_UNIXTIME(com_created_date, '%m') = MONTH(NOW()) - 1)
				AND (a.com_status = 1
				OR (a.com_status = 0 AND (b.usr_approved = 0 OR b.usr_rejected = 0)))
				AND a.com_registered_to = 'TM'
			GROUP BY a.com_name) z
		";
        $lastNMonth = Company::findBySql($query)->count();
        $newPercentage = (((int) $thisNMonth - (int) $lastNMonth) / 100);

        $telekom = Company::find()->with(['category'])->telekom;
        $dataProvider = new ActiveDataProvider([
            'query' => $telekom,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('telkom/index', [
                    'dataProvider' => $dataProvider,
                    'totalBusiness' => $totalBusiness,
                    'businessPercentage' => $businessPercentage,
                    'totalBranches' => $totalBranches,
                    'branchPercentage' => $branchPercentage,
                    'totalNewBusiness' => $totalNewBusiness,
                    'newPercentage' => $newPercentage
        ]);
    }

    public function actionTelkomlist() {
        $filter = Json::decode($_GET['filter']);
        $type = (isset($type) ? 1 : 0);
        $page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
        $itemPerPage = (!isset($_GET['itemPerPage'])) ? 10 : $_GET['itemPerPage'];
        $count = 0;
        $connection = Yii::$app->db;
        $query = "
			SELECT a.com_id, a.com_name, a.com_description,
				a.com_email, a.com_phone, a.com_photo, a.com_address,
				CONCAT(c.cit_name,', ',d.reg_name,', ',e.cny_name) AS city,
				f.fsc_valid_start, f.fsc_valid_end, f.fsc_block_datetime,
				f.fsc_payment_currency, f.fsc_payment_datetime, f.fsc_payment_received_datetime,
				g.fes_code, g.fes_name, g.fes_price, f.fsc_status,
				IF(DATEDIFF(NOW(), FROM_UNIXTIME(f.fsc_valid_end)) >= 0, 'muted',
					IF(DATEDIFF(NOW(), FROM_UNIXTIME(f.fsc_valid_end)) >= -1, 'danger',
						IF(DATEDIFF(NOW(), FROM_UNIXTIME(f.fsc_valid_end)) >= -2, 'warning',
							IF(DATEDIFF(NOW(), FROM_UNIXTIME(f.fsc_valid_end)) >= -3, 'info', 'black')))) color
			FROM tbl_company a
			LEFT JOIN tbl_city c ON c.cit_id = a.com_city_id
			LEFT JOIN tbl_region d ON d.reg_id = a.com_region_id
			LEFT JOIN tbl_country e ON e.cny_id = a.com_country_id
			LEFT JOIN tbl_feature_subscription_company f ON f.fsc_com_id = a.com_id
			LEFT JOIN tbl_feature_subscription g ON g.fes_id = f.fsc_fes_id
			WHERE a.com_hq_id = 0
				AND a.com_type = 0
				AND a.com_registered_to = 'TM'
		";
        if (!empty($_GET['filter'])) {
            foreach ($filter as $fl => $v) {
                $query .= "AND " . $fl . " LIKE '%" . $v . "%' ";
            }
        }
        $queryTotal = $query;
        $offset = ($page * $itemPerPage) - $itemPerPage;
        $query = $query . "
			LIMIT " . $itemPerPage . " OFFSET " . $offset;

        $total = $connection->createCommand($queryTotal)->queryAll();
        $model = $connection->createCommand($query)->queryAll();

        if (!empty($_GET['filter'])) {
            $count = count($total);
        } else {
            $query = "
				SELECT a.com_id
			FROM tbl_company a
			LEFT JOIN tbl_city c ON c.cit_id = a.com_city_id
			LEFT JOIN tbl_region d ON d.reg_id = a.com_region_id
			LEFT JOIN tbl_country e ON e.cny_id = a.com_country_id
			LEFT JOIN tbl_feature_subscription_company f ON f.fsc_com_id = a.com_id
			LEFT JOIN tbl_feature_subscription g ON g.fes_id = f.fsc_fes_id
			WHERE a.com_hq_id = 0
				AND a.com_type = 0
				AND a.com_registered_to = 'TM'
			";
            $count = Company::findBySql($query)->count();
        }
        $result = [];
        $result['totalData'] = $count;
        $result['data'] = $model;
        return Json::encode($result);
    }

    public function actionTelkombuy() {
        $data = Json::decode(file_get_contents('php://input'));
        $model = FeatureSubscriptionCompany::find()->where(['fsc_com_id' => $data['com_id']])->one();
        $model->fsc_status = 0;
        $model->save(false);

        // create
        $subscription = new FeatureSubscriptionCompany();
        $subscription->fsc_valid_start = $model->fsc_valid_end + 1;
        $subscription->fsc_valid_end = $model->fsc_valid_end + strtotime('+1 month');
        $subscription->fsc_datetime = time();
        $subscription->fsc_com_id = $data['com_id'];
        $subscription->fsc_status_datetime = time();
        $subscription->fsc_payment_amount = 99;
        $subscription->fsc_payment_datetime = time();
        $subscription->fsc_payment_currency = $model->fsc_payment_currency;
        $subscription->fsc_payment_type = $data['payment'];
        $subscription->fsc_status = 1;
        $subscription->fsc_fes_id = 2;
        $subscription->save(false);
        $fsc_id = Yii::$app->db->getLastInsertID();

        // get all feature subscription detail
        $details = FeatureSubscriptionDetail::find()->where(['fed_fes_id' => 2])->all();
        // insert to feature subscription company detail
        foreach ($details as $det) {
            $detail = new FeatureSubscriptionCompanyDetail();
            $detail->fsd_fet_id = $det->fed_fet_id;
            $detail->fsd_fes_id = $det->fed_fes_id;
            $detail->fsd_free = $det->fed_free;
            $detail->fsd_period = $det->fed_period;
            $detail->fsd_max_query = $det->fed_max_query;
            $detail->fsd_query_left = $det->fed_max_query;
            $detail->fsd_fsc_id = $fsc_id;
            $detail->fsd_last_query_executed = time();
            $detail->save();
        }
    }

    // =============== EXCLUSIVE ====================

    public function actionExclusive($id) {
        $model = FeatureSubscriptionCompanyFree::find()->where(['fsf_com_id' => $id])->one();
        if (empty($model)) {
            $model = new FeatureSubscriptionCompanyFree();
            $model->fsf_com_id = $id;
        } else {
            $model->fsf_valid_start = date('d-m-Y', $model->fsf_valid_start);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->fsf_valid_start = strtotime($model->fsf_valid_start);
            $expired = strtotime('+' . (int) $model->fsf_count_month . ' month', $model->fsf_valid_start);
            $model->fsf_valid_end = $expired;
            if ($model->fsf_fsp_id == 1)
                $model->fsf_deposit = null;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $audit = AuditReport::setAuditReport('exclusive merchant : ' . $model->business->com_name, Yii::$app->user->id, FeatureSubscriptionCompanyFree::className(), $model->fsf_id);
                    if ($audit->save()) {
                        $now = time();
                        $business = Company::findOne($id);
                        $business->com_subscription_biling_status = 1;
                        $business->save(false);

                        $feature = FeatureSubscriptionCompany::find()->where(['fsc_com_id' => $id]);
                        if (!empty($feature->one())) {
                            $feature->one()->fsc_status = 3;
                            $feature->one()->save(false);
                        }

                        // save to subscription company
                        $package = FeatureSubscription::findOne($model->fsf_fes_id);
                        $business = new FeatureSubscriptionCompany();
                        $business->fsc_com_id = $id;
                        $business->fsc_fes_id = $model->fsf_fes_id;
                        $business->fsc_datetime = $now;
                        $business->fsc_valid_start = $model->fsf_valid_start;
                        $business->fsc_valid_end = strtotime('+1 month', $model->fsf_valid_start);
                        $business->fsc_payment_amount = 0;
                        $business->fsc_status = 1;
                        $business->fsc_free = 1;
                        $business->fsc_status_datetime = $now;
                        $business->fsc_payment_currency = $package->fes_currency;
                        $business->fsc_payment_amount = $package->fes_price;
                        $business->fsc_payment_datetime = $now;
                        $business->fsc_payment_received_datetime = $now;
                        $business->fsc_payment_received_by = Yii::$app->user->id;
                        $business->save();
                        $fsc_id = Yii::$app->db->getLastInsertID();

                        // get all feature subscription detail
                        $details = FeatureSubscriptionDetail::find()->where(['fed_fes_id' => $model->fsf_fes_id])->all();
                        // insert to feature subscription company detail
                        foreach ($details as $det) {
                            $detail = new FeatureSubscriptionCompanyDetail();
                            $detail->fsd_fet_id = $det->fed_fet_id;
                            $detail->fsd_fes_id = $det->fed_fes_id;
                            $detail->fsd_free = $det->fed_free;
                            $detail->fsd_period = $det->fed_period;
                            $detail->fsd_max_query = $det->fed_max_query;
                            $detail->fsd_query_left = $det->fed_max_query;
                            $detail->fsd_fsc_id = $fsc_id;
                            $detail->fsd_last_query_executed = $now;
                            $detail->save();
                        }
                    }
                    $transaction->commit();
                    return $this->redirect(['business/exclusive/' . $id]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return $this->redirect(['index']);
            }
        }
        return $this->render('exclusive', [
                    'model' => $model
        ]);
    }

    public function actionSaveexclusive() {
        $data = Json::decode(file_get_contents('php://input'));
        $business = Company::findOne($data['id']);
        $business->com_subscription_biling_status = 1;
        $business->save(false);

        // save to subscription feature company free
        $now = time();
        $start = strtotime($data['data']['start']);
        // expired = start * count month
        $expired = strtotime('+' . $data['data']['count'] . ' month', $start);
        $model = FeatureSubscriptionCompanyFree::find()->where(['fsf_com_id' => $data['id']])->one();
        if (empty($model)) {
            $model = new FeatureSubscriptionCompanyFree();
            $model->fsf_com_id = $data['id'];
            $model->fsf_fes_id = $data['data']['feature'];
            $model->fsf_datetime = $now;
            $model->fsf_valid_start = (int) $start;
            $model->fsf_valid_end = (int) $expired;
            $model->fsf_status = $data['data']['status'];
            $model->fsf_count_month = $data['data']['count'];
            $model->save();
        }

        $package = FeatureSubscription::findOne($data['data']['feature']);
        // find status update to 3
        $businessActive = FeatureSubscriptionCompany::findOne($data['id']);
        $businessActive->fsc_status = 3;
        $businessActive->save(false);

        // save to subscription company
        $business = new FeatureSubscriptionCompany();
        $business->fsc_com_id = $data['id'];
        $business->fsc_fes_id = $data['data']['feature'];
        $business->fsc_datetime = $now;
        $business->fsc_valid_start = (int) $start;
        $business->fsc_valid_end = strtotime('+1 month', $start);
        $business->fsc_payment_amount = 0;
        $business->fsc_status = 1;
        $business->fsc_free = 1;
        $business->fsc_status_datetime = $now;
        $business->fsc_payment_currency = $package->fes_currency;
        $business->fsc_payment_amount = $package->fes_price;
        $business->fsc_payment_datetime = $now;
        $business->fsc_payment_received_datetime = $now;
        $business->fsc_payment_received_by = Yii::$app->user->id;
        $business->save();
        $fsc_id = Yii::$app->db->getLastInsertID();

        // get all feature subscription detail
        $details = FeatureSubscriptionDetail::find()->where(['fed_fes_id' => $data['data']['feature']])->all();
        // insert to feature subscription company detail
        foreach ($details as $det) {
            $detail = new FeatureSubscriptionCompanyDetail();
            $detail->fsd_fet_id = $det->fed_fet_id;
            $detail->fsd_fes_id = $det->fed_fes_id;
            $detail->fsd_free = $det->fed_free;
            $detail->fsd_period = $det->fed_period;
            $detail->fsd_max_query = $det->fed_max_query;
            $detail->fsd_query_left = $det->fed_max_query;
            $detail->fsd_fsc_id = $fsc_id;
            $detail->fsd_last_query_executed = $now;
            $detail->save();
        }
    }

    public function actionPremium($id) {
        $status = $_POST['status'] == "0" ? 1 : 0;
        $model = Company::findOne($id);
        $model->com_premium = $status;
        $model->save(false);

        AuditReport::setAuditReport('premium merchant : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id)->save();

        $arr = [
            'id' => $id,
            'status' => $status
        ];
        return Json::encode($arr);
    }

    // =========== IPAY =============

    public function actionIpay($id) {
        $connection = Yii::$app->db;
        $query = "SELECT com_id, com_ipay88_key, com_ipay88_secret, com_mpay_key, com_mpay_secret FROM tbl_company WHERE com_id = " . $id;
        $model = $connection->createCommand($query)->queryAll();
        return Json::encode($model);
    }

    public function actionSaveipay() {
        $data = Json::decode(file_get_contents('php://input'));
        $model = Company::findOne($data['com_id']);
        $model->com_ipay88_key = $data['com_ipay88_key'];
        $model->com_ipay88_secret = $data['com_ipay88_secret'];
        $model->com_mpay_key = $data['com_mpay_key'];
        $model->com_mpay_secret = $data['com_mpay_secret'];
        $model->save(false);
    }

    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $branch = Company::find()->getBranch($id);
        $branchProvider = new ActiveDataProvider([
            'query' => $branch,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        $offer = Deal::find()->getBusinessOffer($id);
        $offerProvider = new ActiveDataProvider([
            'query' => $offer,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        $loyalty = LoyaltyPointReward::find()->getBusinessLoyalty($id);
        $loyaltyProvider = new ActiveDataProvider([
            'query' => $loyalty,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        $event = Event::find()->getBusinessEvent($id);
        $eventProvider = new ActiveDataProvider([
            'query' => $event,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        $merchant = LoyaltyPointMerchant::find()->getLoyaltyMerchant($id);
        $merchantProvider = new ActiveDataProvider([
            'query' => $merchant,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        $customer = Customer::find()->getBusinessCustomer($id);
        $customerProvider = new ActiveDataProvider([
            'query' => $customer,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
        $follower = Follow::find()->getFollower($id);
        $followerProvider = new ActiveDataProvider([
            'query' => $follower,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
        $notif = NotificationCompany::find()->getNotification($id);
        $notifProvider = new ActiveDataProvider([
            'query' => $notif,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
        $app = ModuleInstalled::find()->getModule($id);
        $appProvider = new ActiveDataProvider([
            'query' => $app,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
        $user = User::find()->getUser($id);
        $userProvider = new ActiveDataProvider([
            'query' => $user,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);
        $subscription = FeatureSubscriptionCompany::find()->getBusiness($id);
        $subscriptionProvider = new ActiveDataProvider([
            'query' => $subscription,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        $segmentProvider = new ActiveDataProvider([
            'query' => SegmentCompany::find()->where('sec_com_id=:com_id', ['com_id' => $id]),
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        if ($this->user->type == 3) {
            $view = 'bsc/view';
        } else {
            $view = 'view';
        }

        return $this->render($view, [
                    'model' => $this->findModel($id),
                    'branchProvider' => $branchProvider,
                    'offerProvider' => $offerProvider,
                    'loyaltyProvider' => $loyaltyProvider,
                    'eventProvider' => $eventProvider,
                    'merchantProvider' => $merchantProvider,
                    'customerProvider' => $customerProvider,
                    'followerProvider' => $followerProvider,
                    'notifProvider' => $notifProvider,
                    'appProvider' => $appProvider,
                    'userProvider' => $userProvider,
                    'subscriptionProvider' => $subscriptionProvider,
                    'segmentProvider' => $segmentProvider,
        ]);
    }

    public function actionSnapearn() {
        $arr = [];
        $id = $_POST['com_id'];
        $snapearn = Company::findOne($id)->com_snapearn;
        if ($snapearn == 0) {
            $model = Company::findOne($id);
            $model->com_snapearn = 1;
            $audit = AuditReport::setAuditReport('snapearn enabled : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id)->save();
            $model->save(false);
            $arr['data'] = 1;
            $arr['html'] = '<i class="fa fa-check"></i> Disabled';
        } else {
            $model = Company::findOne($id);
            $model->com_snapearn = 0;
            $audit = AuditReport::setAuditReport('snapearn disabled : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id)->save();
            $model->save(false);
            $arr['data'] = 0;
            $arr['html'] = '<i class="fa fa-circle-o"></i> Enabled';
        }
        return Json::encode($arr);
    }

    protected function assignFcs($fsc, $com_id, $company, $fes_code) {
        $subscription = FeatureSubscription::getTmPackage($fes_code);
        $fsc->fsc_com_id = $com_id;
        $fsc->fsc_fes_id = $subscription->fes_id;
        $fsc->fsc_datetime = time();
        $fsc->fsc_valid_start = time();
        $fsc->fsc_valid_end = strtotime('+30 day', time());
        $fsc->fsc_status = 1;
        $fsc->fsc_free = 1;
        $fsc->fsc_status_datetime = time();
        $fsc->fsc_payment_amount = $subscription->fes_price;
        $fsc->fsc_payment_currency = $subscription->fes_currency;
        $fsc->fsc_payment_type = 1;
        $fsc->fsc_payment_datetime = time();
        $fsc->fsc_payment_received_datetime = time();
        $fsc->fsc_payment_received_by = 1;
    }

    protected function assignPaymentMethod($company) {
        $model = new PosPaymentMethod();
        $model->pym_com_id = $company->com_id;
        $model->pym_name = 'Cash';
        $model->pym_pyt_id = 0;
        $model->pym_pyg_id = null;
        $model->pym_action = 1;
        $model->save();

        $model = new PosPaymentMethod();
        $model->pym_com_id = $company->com_id;
        $model->pym_name = 'Manage Pay';
        $model->pym_pyt_id = 1;
        $model->pym_pyg_id = 1;
        $model->pym_action = 1;
        $model->save();
    }

    protected function assignTableArea($company) {
        $model = new TableArea();
        $model->tba_com_id = $company->com_id;
        $model->tba_description = 'Default';
        $model->save();
    }

    protected function assignModule($com_id, $company) {
        $modulInstall = Module::find()->where('mod_id IN (12,19,15)')->All();
        foreach ($modulInstall as $module) {
            $moduleInstalled = new ModuleInstalled();
            $moduleInstalled->mos_mod_id = $module->mod_id;
            $moduleInstalled->mos_com_id = $com_id;
            $moduleInstalled->mos_datetime = time();
            $moduleInstalled->mos_active = 'Y';
            $moduleInstalled->mos_key = str_replace('-', '', crc32($com_id));
            $moduleInstalled->mos_secret = md5($com_id);
            $moduleInstalled->mos_page_builder = 1;
            $moduleInstalled->mos_delete = 0;
            $moduleInstalled->save();
        }
    }

    protected function assignEmail($com_id, $company) {
        $systemMessage = new SystemMessage;
        $typeMessage = 17;
        $categoryMessage = 'business_signup';
        $email = $company->com_email;
        $com_name = $company->com_name;
        $parsersData[] = array('[name]', htmlspecialchars_decode($com_name, ENT_QUOTES));
        $systemMessage->Parser($typeMessage, $categoryMessage, $email, $parsersData);
    }

    public function actionPartnercreate() {
        $model = new Company();
        $query = "
			SELECT a.com_category_id, a.com_category, a.com_parent_category_id, a.com_icon,
				b.com_category_id AS parent_id, b.com_category AS parent
			FROM tbl_company_category a
			LEFT OUTER JOIN tbl_company_category b
				ON a.com_parent_category_id = b.com_category_id
			WHERE a.com_parent_category_id <> 0
				AND b.com_category_type = 2
			ORDER BY b.com_category
		";
        $connection = Yii::$app->db;
        $categories = $connection->createCommand($query)->queryAll();

        if ($model->load($_POST)) {
            $model->attributes = $_POST['Company'];
            $usr_email = User::find()->where(['usr_email' => $model->com_email])->one()->usr_email;
            if ($usr_email) {
                Yii::$app->getSession()->setFlash('error', 'Email is unavailable!');
                return $this->redirect(['partnercreate']);
            }
            $usr_name = User::find()->where(['usr_username' => $model->com_name])->one()->usr_username;
            if ($usr_name) {
                Yii::$app->getSession()->setFlash('error', 'Username is unavailable!');
                return $this->redirect(['partnercreate']);
            }
            $city = '';
            if ($_POST['Company']['com_in_mall'] === 'on') {
                // get from mall
                $mal_id = $_POST['Mall']['mal_id'];
                $model_mall = Mall::findOne($mal_id);
                $model->com_address = $model_mall->mal_address;
                $model->com_postcode = $model_mall->mal_postcode;
                $model->com_latitude = $model_mall->mal_lat;
                $model->com_longitude = $model_mall->mal_lng;
                $model->com_city_id = $model_mall->mal_city_id;
                $model->com_region_id = $model_mall->mal_region_id;
                $model->com_country_id = $model_mall->mal_country_id;
                $model->com_in_mall = 1;

                $cit_name = City::findOne($model_mall->mal_city_id)->cit_name;
                $reg_name = Region::findOne($model_mall->mal_region_id)->reg_name;
                $cny_name = Country::findOne($model_mall->mal_country_id)->cny_name;
            } else {
                $cny_id = Region::find()->where(['reg_id' => $reg_id])->one()->reg_country_id;
                $cit_name = City::findOne($model->com_city_id)->cit_name;
                $reg_name = Region::findOne($model->com_region_id)->reg_name;
                $cny_name = Country::findOne($cny_id)->cny_name;
            }
            $model->com_city = $cit_name . ', ' . $reg_name . ', ' . $cny_name;
            $model->com_hq_id = 0;
            $model->com_usr_id = 0;
            $model->com_type = 1;
            $model->com_created_date = time();
            if ($model->save()) {
                $com_id = Yii::$app->db->getLastInsertId();
                if ($_POST['Company']['com_in_mall'] === 'on') {
                    // insert to mall merchant
                    $mall_merchant = new MallMerchant();
                    $mall_merchant->attributes = $_POST['MallMerchant'];
                    $mall_merchant->mam_com_id = $com_id;
                    $mall_merchant->mam_mal_id = $mal_id;
                    $mall_merchant->save();
                }

                Yii::$app->getSession()->setFlash('message', $model->com_name . ' Added!');
                return $this->redirect(['partnercreate']);
            }
        }
        return $this->render('createpartner', [
                    'model' => $model,
                    'categories' => $categories,
                    'id' => (isset($_GET['id']) ? $_GET['id'] : 0)
        ]);
    }

    public function actionSave() {
        if (isset($_POST)) {
            $connection = Yii::$app->db;
            $usr_email = Company::find()->where(['com_email' => $_POST['Company']['com_email']])->one();
            if (!empty($usr_email)) {
                Yii::$app->getSession()->setFlash('error', 'Email is unavailable!');
                return $this->redirect(['create']);
            }
            $type = (int) (rtrim($_POST['Company']['com_type'], '/'));
            if ($type != 1) {
                $query = "
					INSERT INTO tbl_user
					SET
						usr_username = '" . addslashes($_POST['Company']['com_name']) . "',
						usr_email = '" . $_POST['Company']['com_email'] . "',
						usr_type_id = 2,
						usr_createdate = " . time() . ",
						usr_last_ip = '" . $_SERVER['REMOTE_ADDR'] . "',
						usr_last_ip_numeric = " . ((int) ip2long($_SERVER['REMOTE_ADDR'])) . ",
						usr_rights = 1234567
				";
                $insertUser = $connection->createCommand($query)->execute();
            }

            $com_usr_id = Yii::$app->db->getLastInsertId();
            if ($type == 1) {
                $com_usr_id = "''";
            }

            $city = '';
            if (!empty($_POST['Company']['com_city_id'])) {
                $city = City::find()->where(['cit_id' => $_POST['Company']['com_city_id']])->one()->cit_name;
            }

            $reg_id = City::find()->where(['cit_id' => $_POST['Company']['com_city_id']])->one()->cit_region_id;
            $cny_id = Region::find()->where(['reg_id' => $reg_id])->one()->reg_country_id;
            $query = "
				INSERT INTO tbl_company
				SET
					com_hq_id = " . $_POST['Company']['com_hq_id'] . ",
					com_usr_id = " . $com_usr_id . ",
					com_name = '" . addslashes($_POST['Company']['com_name']) . "',
					com_description = '" . addslashes($_POST['Company']['com_description']) . "',
					com_subcategory_id = " . $_POST['Company']['com_subcategory_id'] . ",
					com_address = '" . addslashes($_POST['Company']['com_address']) . "',
					com_postcode = '" . $_POST['Company']['com_postcode'] . "',
					com_city = '" . $city . "',
					com_city_id = " . $_POST['Company']['com_city_id'] . ",
					com_region_id = " . $reg_id . ",
					com_country_id = " . $cny_id . ",
					com_latitude = '" . $_POST['Company']['com_latitude'] . "',
					com_longitude = '" . $_POST['Company']['com_longitude'] . "',
					com_phone = '" . $_POST['Company']['com_phone'] . "',
					com_fax = '" . $_POST['Company']['com_fax'] . "',
					com_email = '" . $_POST['Company']['com_email'] . "',
					com_website = '" . $_POST['Company']['com_website'] . "',
					com_size = '" . $_POST['Company']['com_size'] . "',
					com_nbrs_employees = '" . $_POST['Company']['com_nbrs_employees'] . "',
					com_fb = '" . $_POST['Company']['com_fb'] . "',
					com_twitter = '" . $_POST['Company']['com_twitter'] . "',
					com_type = " . $type . ",
					com_photo = '" . $_POST['Company']['com_photo'] . "',
					com_reg_num = '" . $_POST['Company']['com_reg_num'] . "',
					com_agent_code = '" . $_POST['Company']['com_agent_code'] . "',
					com_business_name = '" . $_POST['Company']['com_business_name'] . "',
					com_created_date = " . time() . "
			";
            $insertBusiness = $connection->createCommand($query)->execute();
            $com_id = Yii::$app->db->getLastInsertId();

            if ($type != 1) {
                // send email
                $parsers = [];
                $name = 'business_signup';
                $parsers[] = array('[name]', $_POST['Company']['com_name']);
                $message = new SystemMessage;
                $message->parser(2, $name, $_POST['Company']['com_email'], $parsers);

                // feature subscription
                $feature = FeatureSubscription::find()->where(['fes_price' => 0, 'fes_status' => 1])->one();
                if ($feature) {
                    $subscription = new FeatureSubscriptionCompany();
                    $features = [];
                    $features['fsc_com_id'] = $com_id;
                    $features['fsc_fes_id'] = $feature->fes_id;
                    $features['fsc_datetime'] = time();
                    $features['fsc_valid_start'] = time();
                    $features['fsc_valid_end'] = strtotime("+" . $feature->fes_valid_day . " day", time());
                    $features['fsc_status'] = '1';
                    $features['fsc_status_datetime'] = time();
                    $features['fsc_payment_amount'] = '0';
                    $features['fsc_payment_currency'] = $feature->fes_currency;
                    $subscription->attributes = $features;
                    $subscription->save();

                    $fsc_id = Yii::$app->db->getLastInsertId();
                    $featureDetail = FeatureSubscriptionDetail::find()->where(['fed_fes_id' => $feature->fes_id])->all();
                    foreach ($featureDetail as $detail) {
                        $featureCompany = new FeatureSubscriptionCompanyDetail();
                        $featCompany = [];
                        $featCompany['fsd_fet_id'] = $data->fed_fet_id;
                        $featCompany['fsd_fes_id'] = $data->fed_fes_id;
                        $featCompany['fsd_fsc_id'] = $fsc_id;
                        $featCompany['fsd_free'] = $data->fed_free;
                        $featCompany['fsd_period'] = $data->fed_period;
                        $featCompany['fsd_max_query'] = $data->fed_max_query;
                        $featCompany['fsd_query_left'] = $fsd_max_query;
                        $featureCompany->attributes = $featCompany;
                        $featureCompany->save();
                    }
                }
            }

            Yii::$app->getSession()->setFlash('message', $_POST['Company']['com_name'] . ' Added!');
            if ((int) $_POST['Company']['com_type'] == 1)
                return $this->redirect('business/create/?type=1');
            else
                return $this->redirect('business/create/');
        }
    }

    // ========== MALL ===============

    public function actionMall($q = null) {
        $query = "
			SELECT mal_id, mal_name
			FROM tbl_mall
			WHERE mal_status = 1
				AND mal_name LIKE '%" . $q . "%'
			ORDER BY mal_name
			LIMIT 10";
        $connection = Yii::$app->db;
        $query = $connection->createCommand($query)->queryAll();
        $return = [];
        foreach ($query as $row) {
            $return[]['value'] = $row['mal_id'] . ': ' . $row['mal_name'];
        }
        return Json::encode($return);
    }

    public function actionMallcategory($id) {
        $model = \common\models\MallCategory::find()
                ->select('mac_id, mac_name')
                ->where('mac_mal_id = :mal_id', [':mal_id' => $id])
                ->orderBy('mac_name')
                ->all();
        // $html = '';
        // foreach($model as $row) {
        //     $html .= '<option value="' . $row->mac_id . '">' . $row->mac_name . '</option>';
        // }
        // echo $html;
        return Json::encode($model);
    }

    public function actionMalllist() {
        $filter = Json::decode($_GET['filter']);
        $page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
        $itemPerPage = (!isset($_GET['itemPerPage'])) ? 10 : $_GET['itemPerPage'];
        $count = 0;
        $connection = Yii::$app->db;
        $query = "
			SELECT a.mal_id, a.mal_name, a.mal_address,a.mal_photo,
				CONCAT(b.cit_name, ', ', c.reg_name, ', ', d.cny_name) AS city, a.mal_postcode
			FROM tbl_mall a
			LEFT JOIN tbl_city b ON b.cit_id = a.mal_city_id
			LEFT JOIN tbl_region c ON c.reg_id = a.mal_region_id
			LEFT JOIN tbl_country d ON d.cny_id = a.mal_country_id
			WHERE a.mal_id > 0
		";
        if (!empty($_GET['filter'])) {
            foreach ($filter as $fl => $v) {
                $query .= "AND " . $fl . " LIKE '%" . $v . "%' ";
            }
        }
        $queryTotal = $query;
        $offset = ($page * $itemPerPage) - $itemPerPage;
        $query = $query . "
			LIMIT " . $itemPerPage . " OFFSET " . $offset;

        $total = $connection->createCommand($queryTotal)->queryAll();
        $model = $connection->createCommand($query)->queryAll();

        if (!empty($_GET['filter'])) {
            $count = count($total);
        } else {
            $query = "
				SELECT a.mal_id, a.mal_name, a.mal_address,a.mal_photo,
					CONCAT(b.cit_name, ', ', c.reg_name, ', ', d.cny_name) AS city, a.mal_postcode
				FROM tbl_mall a
				LEFT JOIN tbl_city b ON b.cit_id = a.mal_city_id
				LEFT JOIN tbl_region c ON c.reg_id = a.mal_region_id
				LEFT JOIN tbl_country d ON d.cny_id = a.mal_country_id
				WHERE a.mal_id > 0
			";
            $count = Mall::findBySql($query)->count();
        }
        $result = [];
        $result['totalData'] = $count;
        $result['data'] = $model;
        return Json::encode($result);
    }

    public function actionMallminilist() {
        $model = Mall::find()->orderBy('mal_name ASC')->all();
        $html = '';
        foreach ($model as $row) {
            $html .= '<option value="' . $row['mal_id'] . '">' . $row['mal_name'] . '</option>';
        }
        echo $html;
    }

    public function actionMalldetail($id) {
        $connection = Yii::$app->db;
        $query = "
			SELECT a.mal_id, a.mal_name, a.mal_address,a.mal_photo,
				CONCAT(b.cit_name, ', ', c.reg_name, ', ', d.cny_name) AS city,
				a.mal_postcode, a.mal_lat, a.mal_lng
			FROM tbl_mall a
			LEFT JOIN tbl_city b ON b.cit_id = a.mal_city_id
			LEFT JOIN tbl_region c ON c.reg_id = a.mal_region_id
			LEFT JOIN tbl_country d ON d.cny_id = a.mal_country_id
			WHERE a.mal_id = " . $id;
        $model = $connection->createCommand($query)->queryAll();
        return Json::encode($model);
    }

    public function actionCreatemall() {
        $model = new Mall;
        return $this->render('mall/create', [
                    'model' => $model
        ]);
    }

    public function actionMallsave() {
        $model = new Mall;
        if ($model->load($_POST)) {
            $model->attributes = $_POST['Mall'];

            $reg_id = City::find()->where(['cit_id' => $model->mal_city_id])->one()->cit_region_id;
            $cny_id = Region::find()->where(['reg_id' => $reg_id])->one()->reg_country_id;
            $model->mal_region_id = $reg_id;
            $model->mal_country_id = $cny_id;
            $model->mal_email = $model->mal_email;
            $model->mal_website = $model->mal_website;
            $model->mal_lat = $model->mal_lat;
            $model->mal_lng = $model->mal_lng;
            $model->mal_datetime = time();
            $model->mal_photo = $_POST['Mall']['mal_photo'];
            if ($model->save()) {
                return $this->redirect(['mall']);
            }
        }
    }

    public function actionMallupdate($id) {
        $data = Json::decode(file_get_contents('php://input'));
        $field = $data['field'];
        $value = $data['value'];
        $model = Mall::findOne($id);
        // find mall merchant
        if ($field == 'mal_address') {
            $query = "SELECT mam_com_id FROM tbl_mall_merchant WHERE mam_mal_id = " . $id;
            $model = Yii::$app->db->createCommand($query)->queryAll();
            $mall = '(';
            foreach ($model as $row) {
                $mall .= $row['mam_com_id'] . ', ';
            }
            $mall = rtrim($mall, ', ');
            $mall .= ')';

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $query = "UPDATE tbl_company SET com_address = '" . $value . "' WHERE com_id IN " . $mall;
                Yii::$app->db->createCommand($query)->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else if ($field == 'mal_postcode') {
            $query = "SELECT mam_com_id FROM tbl_mall_merchant WHERE mam_mal_id = " . $id;
            $model = Yii::$app->db->createCommand($query)->queryAll();
            $mall = '(';
            foreach ($model as $row) {
                $mall .= $row['mam_com_id'] . ', ';
            }
            $mall = rtrim($mall, ', ');
            $mall .= ')';

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $query = "UPDATE tbl_company SET com_postcode = '" . $value . "' WHERE com_id IN " . $mall;
                Yii::$app->db->createCommand($query)->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } elseif ($field == 'city') {
            $query = "SELECT mam_com_id FROM tbl_mall_merchant WHERE mam_mal_id = " . $id;
            $model = Yii::$app->db->createCommand($query)->queryAll();
            $mall = '(';
            foreach ($model as $row) {
                $mall .= $row['mam_com_id'] . ', ';
            }
            $mall = rtrim($mall, ', ');
            $mall .= ')';

            // edit address in mall with criteria: com_id = mam_com_id
            $city = City::findOne($value)->cit_name;
            $reg_id = City::findOne($value)->cit_region_id;
            $region = Region::findOne($reg_id)->reg_name;
            $cny_id = Region::findOne($reg_id)->reg_country_id;
            $country = Country::findOne($cny_id)->cny_name;
            $result = $city . ', ' . $region . ', ' . $country;

            // insert into company city region and country
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $query = "
					UPDATE tbl_company
					SET
						`com_city` = '" . $result . "',
						`com_city_id` = " . $value . ",
						`com_region_id` = " . $reg_id . ",
						`com_country_id` = " . $cny_id . "
					WHERE `com_id` IN " . $mall;
                Yii::$app->db->createCommand($query)->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
            return $result;
        } else {
            // else
            $model->$field = addslashes($value);
            $model->save();
        }
    }

    public function actionMalldelete() {
        $data = Json::decode(file_get_contents('php://input'));
        if (isset($data)) {
            $id = $data['mal_id'];
            $merchant = MallMerchant::find()->where(['mam_mal_id' => $id])->all();
            foreach ($merchant as $row) {
                $merchant->delete();
            }
            // delete mall
            $model = Mall::findOne($id);
            $model->delete();
        }
    }

    public function actionMallsavemap($id) {
        $data = Json::decode(file_get_contents('php://input'));
        $model = Mall::findOne($id);
        $model->mal_lat = $data['lat'];
        $model->mal_lng = $data['lng'];
        $model->save();

        // update to businesses
        $malles = MallMerchant::find()->where(['mam_mal_id' => $id])->all();
        foreach ($malles as $row) {
            // edit address in mall with criteria: com_id = mam_com_id
            $business = Company::findOne($row->mam_com_id);
            $business->com_latitude = $data['lat'];
            $business->com_longitude = $data['lng'];
            $business->save();
        }
        return 'New location has been updated!';
    }

    // =========== MALL MERCHANT =============

    public function actionMerchantlist($id) {
        $connection = Yii::$app->db;
        $query = "
			SELECT a.mam_id, b.com_name, a.mam_mal_id, a.mam_floor, a.mam_unit_number
			FROM tbl_mall_merchant a
			INNER JOIN tbl_company b ON b.com_id = a.mam_com_id
			WHERE a.mam_mal_id = " . $id . "
			ORDER BY b.com_name
		";
        $model = $connection->createCommand($query)->queryAll();
        return Json::encode($model);
    }

    public function actionMerchantsave() {
        $data = Json::decode(file_get_contents('php://input'));
        $data = $data['data'];
        $connection = Yii::$app->db;
        // $transaction = $connection->beginTransaction();
        if (isset($data)) {
            $connection = Yii::$app->db;
            $com_id = (int) $data['com_name'];
            $floor = addslashes($data['floor']);
            $unit = addslashes($data['unit']);
            $mal_id = (int) $data['mal_id'];
            if (isset($data['id'])) {
                $id = (int) $data['id'];
                $model = MallMerchant::findOne($id);
                $model->mam_com_id = $com_id;
                $model->mam_floor = $floor;
                $model->mam_unit_number = $unit;
                $model->mam_mal_id = $mal_id;
                $model->save();
            } else {
                $model = new MallMerchant();
                $model->mam_com_id = $com_id;
                $model->mam_floor = $floor;
                $model->mam_unit_number = $unit;
                $model->mam_mal_id = $mal_id;
                $model->save();
            }
        }
    }

    public function actionMerchantdelete() {
        $data = Json::decode(file_get_contents('php://input'));
        $model = MallMerchant::findOne($data['id']);
        $model->delete();
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $user = User::findOne($model->com_usr_id);
        $model->scenario = 'update-profile';
        $model->com_description = \yii\helpers\Html::decode($model->com_description);
        $model->com_sales_order = date('d/m/Y', $model->com_sales_order);
        $model->tag = $model->getTag($id);
        $unit_merchant = [];
        \Yii::$app->session->set('company', serialize($model));

        // ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->com_in_mall == 1) {
            if ($model->marchant instanceof MallMerchant) {
                $mall = Mall::findOne($model->marchant->mam_mal_id);
                if ($mall instanceof Mall) {
                    $model->isMallManaged = $mall->mal_key == true;
                    $unit_merchant = FloorPlanMallMerchant::listunit($model->marchant->mam_id);
                }
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            if(!empty($model->com_sales_order))
                $model->com_sales_order = strtotime($model->com_sales_order);
            $changed_attributes = $model->getChangedAttribute(['com_timezone', 'com_in_mall', 'com_mac_id']);
            $user->usr_email = $model->com_email;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save() && $user->save(false)) {
                    $audit = AuditReport::setAuditReport('update business : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id, $changed_attributes);
                    if ($audit->save()) {
                        \Yii::$app->session->set('company', '');
                        $model->setTag();
                        $transaction->commit();
                        $this->setMessage('save','success', 'Business updated successfully!');
                    }
                    return $this->redirect([$this->getRememberUrl()]);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                throw $e;
            }
        }
        return $this->render('update', [
            'model' => $model,
            'unit_merchant' => $unit_merchant
        ]);
    }

    public function actionUpdatemall($id) {
        $model = $this->findModel($id);
        $model->scenario = 'update-profile';
        $model->com_description = \yii\helpers\Html::decode($model->com_description);
        $model->fes_id = $model->currentSubscription ? $model->currentSubscription->featureSubscription->fes_code : '';
        $user = User::findOne($model->com_usr_id);
        Yii::$app->session->set('company', serialize($model));

        $unit_merchant = [];

        if ($model->marchant instanceof MallMerchant) {
            $mall = Mall::findOne($model->marchant->mam_mal_id);
            if ($mall instanceof Mall) {
                $unit_merchant = FloorPlanMallMerchant::listunit($model->marchant->mam_id);
            }
        }
        if ($model->load(Yii::$app->request->post())) {
            $changed_attributes = $model->getChangedAttribute(['com_timezone', 'com_mac_id', 'com_subcategory_id', 'com_category_id']);
            $user->usr_email = $model->com_email;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save() && $user->save(false)) {
                    \Yii::$app->session->set('company', '');
                    $model->setTag();
                    $audit = AuditReport::setAuditReport('update store : ' . $model->com_name, \Yii::$app->user->identity->id, Company::className(), $model->com_id, $changed_attributes);
                    if ($audit->save()) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                throw $e;
            }
        }
        return $this->render('update_mall', [
            'model' => $model,
            'unit_merchant' => $unit_merchant
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->com_status = 0;
        if($model->save(false)) {
            $audit = AuditReport::setAuditReport('unactivate business : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id)->save();
            $this->setMessage('save', 'success', 'Merchant has been unactivated!');
        } else {
            $this->setMessage('save', 'error', 'Merchant unactivated failed!<br/>'.General::extactErrorModel($model->getErrors()));
        }
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionForcedelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // if ($model->com_type == 1)
            $user = User::deleteAll('usr_id = :usr_id', [':usr_id' => $model->com_usr_id]);
            $subscription = FeatureSubscriptionCompany::deleteAll('fsc_com_id = :com_id', [':com_id' => $id]);
            $free = FeatureSubscriptionCompanyFree::deleteAll('fsf_com_id = :com_id', [':com_id' => $id]);
            // delete activity
            // delete advertisement
            // delete appointment
            // delete catalogue
            // delete checkin
            // delete customer
            // delete deal
            $offer = Deal::deleteAll('del_com_id = :com_id', [':com_id' => $id]);
            // delete email marketing
            // delete engager
            // delete feature subscription
            // delete feature subscription free
            // delete hardware
            // delete hardware company
            $hardware = HardwareCompany::deleteAll('hac_com_id = :com_id', [':com_id' => $id]);
            // delete ibeacon
            // delete mall merchant
            // delete menu
            // delete menu transaction
            // delete on all related table
            $file = Yii::$app->params['businessUrl'] . $model->com_photo;
            \common\components\helpers\S3::Delete($file);
            $file = Yii::$app->params['businessUrl'] . $model->com_banner_photo;
            \common\components\helpers\S3::Delete($file);
            if ($model->delete()) {
                $audit = AuditReport::setAuditReport('delete business : ' . $model->com_name, Yii::$app->user->id, Company::className(), $model->com_id)->save();
                $transaction->commit();
                return $this->goBack();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }

    // statistic
    public function actionStatistic() {
        return $this->render('statistic', [
                        // 'model' => $data
        ]);
    }

    public function actionStat() {
        $json = '
			[
				{
					"key": "Business Growth",
					"area": true,
					"values": [
		';
        $connection = Yii::$app->db;
        $query = "
			SELECT C.com_created_date AS `date`,
			(
				SELECT COUNT(C2.com_id) FROM tbl_company C2 WHERE
					DAY(FROM_UNIXTIME(C2.com_created_date)) = DAY(FROM_UNIXTIME(C.com_created_date))
			) AS total
			FROM tbl_company C
			WHERE FROM_UNIXTIME(C.com_created_date) > DATE_SUB(now(), INTERVAL 6 MONTH)
			ORDER BY C.com_id ASC
		";
        $model = $connection->createCommand($query)->queryAll();
        foreach ($model as $row) {
            $json .= '[ ' . $row['date'] . '000, ' . $row['total'] . '], ';
        }
        $json = rtrim($json, ', ');
        $json .= '] } ]';
        return $json;
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPoints($id) {
        $filter = Json::decode($_GET['filter']);
        $page = (!isset($_GET['page'])) ? 1 : $_GET['page'];
        $itemPerPage = (!isset($_GET['itemPerPage'])) ? 10 : $_GET['itemPerPage'];
        $count = 0;
        $connection = Yii::$app->db;
        $query = "
			SELECT lpm_id, lpm_cross_redeem, lpm_total_point, lpm_datetime,
				lpm_current_point, lpm_request_point,
				lpm_amount, lpm_currency, lpm_approve, lpm_price
			FROM tbl_loyalty_point_merchant
			WHERE lpm_com_id = " . $id . "
		";
        if (!empty($_GET['filter'])) {
            foreach ($filter as $fl => $v) {
                $query .= "AND " . $fl . " LIKE '%" . $v . "%' ";
            }
        }
        $query .= "ORDER BY lpm_id DESC ";
        $queryTotal = $query;
        $offset = ($page * $itemPerPage) - $itemPerPage;
        $query = $query . "
			LIMIT " . $itemPerPage . " OFFSET " . $offset;

        $total = $connection->createCommand($queryTotal)->queryAll();
        $model = $connection->createCommand($query)->queryAll();

        if (!empty($_GET['filter'])) {
            $count = count($total);
        } else {
            $query = "
				SELECT lpm_id, lpm_cross_redeem, lpm_total_point, lpm_datetime,
					lpm_current_point, lpm_request_point,
					lpm_amount, lpm_currency, lpm_approve, lpm_price
				FROM tbl_loyalty_point_merchant
				WHERE lpm_com_id = " . $id . "
				";
            $count = LoyaltyPointMerchant::findBySql($query)->count();
        }
        $result = [];
        $result['totalData'] = $count;
        $result['data'] = $model;
        return Json::encode($result);
    }

    public function actionChangepoint($id) {
        $companyModel = Company::findOne($id);
        $LoyaltyPointRate = LoyaltyPointRate::find()
                ->where(['lpe_status' => '1'])
                ->orderBy('lpe_id')
                ->all();

        return $this->renderPartial('changepoint', [
                    'model' => $companyModel,
                    'loyaltyPointRate' => $LoyaltyPointRate
                        ], true, true);
    }

    public function actionBuypoint() {
        if (!empty($_POST)) {
            $com_id = $_POST['com_id'];
            $buy_point = $_POST['point'];
            $point_current = $_POST['point_current'];
            $lpm_amount = $_POST['amount'];
            $lpm_currency = $_POST['currency'];
            $lpm_price = $_POST['price'];
            $business = Company::findOne($com_id);
            $pointMerchant = new LoyaltyPointMerchant();
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();

            if (!empty($business)) {
                if (empty($_POST['point'])) {
                    $buy_point = 0;
                }
                $point = $business->com_point + $buy_point;
                $business->com_point = $point;

                /* insert point transaction to table LoyaltyPointHistory  * */
                $pointMerchant->lpm_com_id = $com_id;
                $pointMerchant->lpm_usr_id = Yii::$app->user->id;
                $pointMerchant->lpm_cross_redeem = 'Y';
                $pointMerchant->lpm_datetime = time();
                $pointMerchant->lpm_current_point = $point_current;
                $pointMerchant->lpm_request_point = $buy_point;
                $pointMerchant->lpm_total_point = $point;
                $pointMerchant->lpm_amount = $lpm_amount;
                $pointMerchant->lpm_currency = $lpm_currency;
                $pointMerchant->lpm_price = $lpm_price;
                if ($pointMerchant->save()) {
                    /* Update Point data on table company */
                    $query = "update tbl_company set com_point ='" . $point . "' where com_id=" . $com_id;
                    $point = (!empty($business->currency) ? $business->currency->cur_symbol : '') . ' ' . Yii::$app->formatter->asDecimal($business->com_point);
                    if ($connection->createCommand($query)->execute()) {
                        $return = array('data' => 1, 'value' => $point, 'msg' => 'successful');
                        $transaction->commit();
                    } else {
                        $return = array('data' => 0, 'value' => 0, 'msg' => 'point cant save to table company');
                        $transaction->rollBack();
                    }
                } else {
                    $return = array('data' => 0, 'value' => $point, 'msg' => 'can save to loyalty point!');
                    $transaction->rollBack();
                }
            } else {
                $return = array('data' => 0, 'value' => 0, 'error' => 'invalid input data');
                $transaction->rollBack();
            }
            return Json::encode($return);
        }
    }

    public function actionGetratepoint() {
        $lpe_currency = $_POST['lpe_currency'];
        $model = LoyaltyPointRate::find()
                ->where(['lpe_currency' => $lpe_currency, 'lpe_status' => '1'])
                ->orderBy('lpe_id')
                ->all();
        if ($model) {
            return Json::encode($model[0]);
        } else {
            return Json::encode(array('error' => 'empty result'));
        }
    }

    public function actionUnitlist() {
        if (\Yii::$app->request->isAjax) {
            $response = [];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $floor_id = \Yii::$app->request->post('floor_id');
            $mall_id = \Yii::$app->request->post('mall_id');

            if (!empty($floor_id) && !empty($mall_id)) {
                $response = FloorPlanUnit::listunit($floor_id, $mall_id);
            }

            return $response;
        }
    }

    public function actionFloorlist() {
        if (\Yii::$app->request->isAjax) {
            $response = [];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mall_id = \Yii::$app->request->post('mall_id');

            if (!empty($mall_id)) {
                $response = FloorPlanMall::find()->select(['fpm_name', 'fpm_id'])->where(['fpm_mal_id' => $mall_id])->all();
            }

            return $response;
        }
    }

    public function actionSetfloormerchant() {
        if (\Yii::$app->request->isAjax) {
            $response = ['success' => 0];
            Yii::$app->response->format = Response::FORMAT_JSON;
            $unit_id = \Yii::$app->request->post('unit_id');
            $isNewData = false;

            $session_company = Yii::$app->session->get('company');
            $company = unserialize($session_company);
            if (!($company instanceof Company)) {
                $response = ['success' => 0, 'message' => 'Company not set'];
                return $response;
            }

            if (!empty($unit_id)) {
                $floor_merchant = new FloorPlanMallMerchant();
                if ($company->isNewRecord) {
                    $floor_merchant->fpm_temp_id = $company->idTemp;
                    $isNewData = true;
                } else {
                    $floor_merchant->fpm_mam_id = $company->marchant->mam_id;
                    $isNewData = false;
                }

                $floor_merchant->fpm_fpu_id = $unit_id;

                if ($floor_merchant->save()) {
                    $data = $isNewData ? FloorPlanMallMerchant::listunittemp($company->idTemp) : FloorPlanMallMerchant::listunit($company->marchant->mam_id);
                    $response = ['success' => 1, 'data' => $data];
                } else {
                    $response = ['success' => 0, 'message' => 'Unit has been used'];
                }
            }

            return $response;
        }
    }

    public function actionMerchantunitlist() {
        if (\Yii::$app->request->isAjax) {
            $floor = \Yii::$app->request->post('floor');
            $session_company = Yii::$app->session->get('company');
            $company = unserialize($session_company);
            if ($company->isNewRecord) {
                $dataProvider = FloorPlanMallMerchant::tempDataProvide($company->idTemp, $floor);
            } else {
                $dataProvider = FloorPlanMallMerchant::dataProvide($company->marchant->mam_id, $floor);
            }
            return $this->renderAjax('_merchant_list_unit', [
                        'dataProvider' => $dataProvider
            ]);
        }
    }

    public function actionDeletmerchantunit($id) {
        if (\Yii::$app->request->isAjax) {
            $unitmerchant = FloorPlanMallMerchant::findOne($id)->delete();
            return 1;
        }
    }

    public function actionLoadmerchantfloor() {
        if (\Yii::$app->request->isAjax) {
            $response = ['success' => 0];
            Yii::$app->response->format = Response::FORMAT_JSON;

            $session_company = Yii::$app->session->get('company');
            $company = unserialize($session_company);
            if ($company->isNewRecord) {
                $data = FloorPlanMallMerchant::listunittemp($company->idTemp);
                $response = ['success' => 1, 'data' => $data];
            } else {
                $data = FloorPlanMallMerchant::listunit($company->marchant->mam_id);
                $response = ['success' => 1, 'data' => $data];
            }

            return $response;
        }
    }

    public function actionIsmallmanaged() {
        if (\Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $mall_id = \Yii::$app->request->post('mall_id');
            $mall = Mall::findOne($mall_id);

            return ($mall->mal_key) ? 1 : 0;
        }
    }

    public function actionDeactivate($id) {
        $model = $this->findModel($id);
        $model->setScenario('change_status');
        if ($model->deactivated()) {
            return $this->goBack();
        } else {
            \yii\helpers\VarDumper::dump($model->getErrors());
            exit;
        }

        throw new NotFoundHttpException("There's an Error on system. Please contact your system administrator");
    }

}
