<?php

namespace app\modules\snapearn\controllers;

use Yii;
use app\components\helpers\General;
use app\components\helpers\SnapearnPointSpeciality;
use app\components\helpers\Utc;
use app\controllers\BaseController;
use app\models\Account;
use app\models\Activity;
use app\models\AuditReport;
use app\models\City;
use app\models\Company;
use app\models\CompanySuggestion;
use app\models\FeatureSubscription;
use app\models\FeatureSubscriptionCompany;
use app\models\LoyaltyPointHistory;
use app\models\Mall;
use app\models\MallMerchant;
use app\models\MerchantUser;
use app\models\Module;
use app\models\ModuleInstalled;
use app\models\SnapEarn;
use app\models\SnapEarnPointDetail;
use app\models\SnapEarnRemark;
use app\models\SnapEarnRule;
use app\models\SnapearnPoint;
use app\models\SystemMessage;
use app\models\User;
use app\models\WorkingTime;
use linslin\yii2\curl;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `snapearn` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex()
    {
        Url::remember();
        $this->processOutputType();
        $this->processOutputSize();
        $model = SnapEarn::find()->findCustome();
        
        $this->data_provider = new ActiveDataProvider([
            'query' => $model,
            'sort' => [
                'attributes' => ['sna_receipt_amount', 'sna_point'],
            ],
            'pagination' => [
                'pageSize' => $this->page_size
            ]
        ]);

        $columns = SnapEarn::find()->getExcelColumns();
        $column_styles = SnapEarn::find()->getExcelColumnsStyles();

        $filename = 'SNE-' . date('Y-m-d H:i:s', time()) . '.xlsx';

        // set view name, based on rbac configuration
        $view_filename = Yii::$app->permission_helper->setRbacView('index','index_rbac');

        $save_path = 'sne';

        // additional views output goes here
        $this->getMerchantName();

        return $this->processOutput($view_filename, $columns, $column_styles, $save_path, $filename);
    }

    private function getMerchantName()
    {
        $output = [];

        $merchant_id = Yii::$app->request->get('com_name');

        if($merchant_id) {
            $company = Company::findOne($merchant_id);
            $output['company'] = $company;
            $this->processOutputHooks($output);
        }
    }

    public function actionNewMerchant($id, $to = null)
    {
        // remove session
        $this->removeSession('ses_com_'.$id);
        
        $urlActive = (!empty($to)) ? 'correction/'.$to : 'default/update';
        
        $model = new MerchantUser();
        $model->scenario = 'signup';
        $model->usr_password = md5('123456');
        $model->usr_type_id = 2;
        $model->usr_approved = 0;

        $company = new Company();
        $company->scenario  = 'point';
        // get merchant suggestion
        $suggest = CompanySuggestion::find()
            ->where('cos_sna_id = :id',[
                ':id' => $id
            ])
            ->one();
        // create id mall sugestion default = empty
//        $suggest->cos_mall_id = '';
        // if mall name not empty getting id mall
        if (!empty($suggest->cos_mall)) {
            $cos_mall_id = Mall::find()
                ->where('mal_name = :mal',[
                    ':mal' => $suggest->cos_mall
                ])
                ->one();
            if (isset($cos_mall_id)) {
                $suggest->cos_mall_id = $cos_mall_id->mall_id;
            }
        }

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
                    $company->com_status = 1;
                    $company->com_snapearn = 1;
                    $company->com_snapearn_checkin = 1;
                    $company->com_registered_to = 'EBC';
                    $company->com_created_by = Yii::$app->user->id;

                    $mall_id = Yii::$app->request->post('mall_id');
                    if (!empty($mall_id)) {
                        $mall = Mall::findOne($mall_id)->mal_name;
                        $company->com_name = $company->com_name .' @ ' . $mall;
                    }

                    if ($company->save()) {
                        $com_id = $company->com_id;
                        $cat_id = $company->com_subcategory_id;
                        $snapearn = SnapEarn::findOne($id);
                        $snapearn->sna_com_id = $com_id;
                        $snapearn->sna_cat_id = $this->getCategoryId($cat_id);
                        $snapearn->sna_com_name = $company->com_name;
                        
                        if ($to == 'correction') {
                            $params = [
                                'sna_com_id' => $snapearn->sna_com_id,
                                'sna_cat_id' => $snapearn->sna_cat_id,
                                'sna_com_name' => $snapearn->sna_com_name
                            ];
                            $this->setSession('ses_com_'.$id, $params);
                        } else {
                            $snapearn->save(false);
                        }

                        $suggestion = CompanySuggestion::find()->where('cos_sna_id = :id', [':id' => $id])->one();
                        if (!empty($suggestion)) {
                            $suggestion->cos_com_id = $com_id;
                            $suggestion->save(false);
                        }
                        $com_id = $company->com_id;
                        // $company->setTag();
                        $fsc = new FeatureSubscriptionCompany();
                        $this->assignFcs($fsc, $com_id, $company, $company->fes_id);
                        if ($fsc->save()) {
                            if ($company->com_in_mall = 1) {
                                $mam_model = new MallMerchant();
                                $mam_model->scenario= 'newMerchant';
                                $mam_model->mam_com_id = $company->com_id;
                                $mam_model->mam_mal_id = Yii::$app->request->post('mall_id');
                                $mam_model->save(false);
                            }

                            // SnapEarnPointDetail::savePoint($id, 8);

                            // $audit = AuditReport::setAuditReport('create business from snapearn : ' . $company->com_name, Yii::$app->user->id, Company::className(), $company->com_id);

                            $this->assignModule($com_id, $company);
                            $this->assignEmail($com_id, $company);

                            // Additional point to working time
                            // $param = $id;
                            // $point = WorkingTime::POINT_ADD_NEW_MERCHANT;
                            // $this->addWorkPoint($param, $point);
                            $wrk_ses = $this->getSession('wrk_ses_'.$id);
                            if (!empty($wrk_ses)) {
                                $ses = [];
                                $type = WorkingTime::ADD_NEW_TYPE;
                                $ses['wrk_end'] = $this->workingTime($id);
                                $ses['wrk_description'] = $this->getPoint($type)->spo_name;
                                $ses['wrk_type'] = WorkingTime::ADD_MERCHANT_TYPE;
                                $ses['wrk_rjct_number'] = $type;
                                $ses['wrk_point'] = $this->getPoint($type)->spo_point;
                                $wrk_new_merchant = array_merge($wrk_ses,$ses);
                                $this->setSession('wrk_ses_'.$id, $wrk_new_merchant);
                                $this->saveWorking($id);
                            }

                            $activities = [
                                'Snap Earn - Add New Merchant',
                                'Snapearn - Add New Merchant, ' . $company->com_email . ' on ' . $company->com_name,
                                Company::className(),
                                $company->com_id
                            ];
                            $this->saveLog($activities);
                            
                            $transaction->commit();
                            $this->setMessage('save', 'success', 'Your company has been registered!');
                            return $this->render('success');
                        } else {
                            $transaction->rollback();
                            throw new HttpException(404, 'Cant insert to subscription');
                        }
                    } else {
                        $transaction->rollback();
                        $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                    }
                } else {
                    $transaction->rollback();
                    $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                }
            } catch (Exception $e) {
                $transaction->rollback();
                throw $e;
            }
        }
        return $this->render('new', [
            'company' => $company,
            'suggest' => $suggest
        ]);
    }

    public function actionAjaxExisting($id,$to = null)
    {
        // remove session
        $this->removeSession('ses_com_'.$id);
        
        $model = $this->findModel($id);
        $urlActive = (!empty($to)) ? 'correction/'.$to : 'default/update';
        
        if ($model->load(Yii::$app->request->post())) {
            $model->sna_com_id = (int)Yii::$app->request->post('com_id');
            $company = Company::findOne($model->sna_com_id);
            $cat_id = $this->getCategoryId($company->com_subcategory_id);
            $model->sna_cat_id = $cat_id;
            $model->sna_com_name = $company->com_name;
            
            $wrk_ses = $this->getSession('wrk_ses_'.$id);
            if (!empty($wrk_ses)) {
                $type = WorkingTime::ADD_EXISTING_TYPE;
                $ses['wrk_end'] = $this->workingTime($id);
                $ses['wrk_description'] = $this->getPoint($type)->spo_name;
                $ses['wrk_type'] = WorkingTime::ADD_MERCHANT_TYPE;
                $ses['wrk_rjct_number'] = $type;
                $ses['wrk_point'] = $this->getPoint($type)->spo_point;
                $wrk_new_merchant = array_merge($wrk_ses,$ses);
                $this->setSession('wrk_ses_'.$id, $wrk_new_merchant);
                $this->saveWorking($id);
            }
            
            if ($to == 'correction') {
                $params = [
                    'sna_com_id' => $model->sna_com_id,
                    'sna_cat_id' => $model->sna_cat_id,
                    'sna_com_name' => $model->sna_com_name
                ];
                $this->setSession('ses_com_'.$id, $params);
                $this->setMessage('save', 'success', 'Merchant successfully saved!');
                return $this->redirect([$urlActive.'?id='.$id]);
            } else {
                if ($model->sna_com_id > 0) {
                    if ($model->save(false)) {
                        $activities = [
                            'Snap Earn - Add Existing Merchant',
                            'Snapearn (' . $model->sna_id . ') - Add Existing Merchant, ' . $company->com_email . ' on ' . $company->com_name,
                            SnapEarn::className(),
                            $company->com_id
                        ];
                        $this->saveLog($activities);

                        $this->setMessage('save', 'success', 'Merchant successfully saved!');
                        return $this->redirect([$urlActive.'?id='.$id]);
                    } else {
                        $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                        return $this->redirect([$urlActive.'?id='.$id]);
                    }
                } else {
                    $this->setMessage('save', 'error', 'Merchant not selected!');
                    return $this->redirect([$urlActive.'?id='.$id]);
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('existing', [
                'model' => $model
            ]);
        }
        return $this->redirect(['update?id='.$id]);
    }


    public function actionToUpdate($id)
    {
        // $point_config = new SnapearnPointSpeciality;
        // $point_config->getActivePoint($id);
        
        $this->checkSession($id);
        $model = $this->findModel($id);
        if (empty($model->member)) {
            $this->setMessage('save', 'error', "Manis user is not set!, Please contact your web administrator this snap number <strong>' $id '</strong>");
            return $this->redirect(Url::previous());
        } elseif ((empty($model->newSuggestion)  && empty($model->sna_com_id))  || (!empty($model->sna_com_id) && empty($model->merchant))) {
            $this->setMessage('save', 'error', "This Snap and Earn doesn't set any merchant or suggestion!, Please contact your web administrator this snap id <strong>' $id '</strong>");
            return $this->redirect(Url::previous());
        }

        // working time start session
        $wrk_ses = [
            'wrk_by' => Yii::$app->user->id,
            'wrk_param_id' => $id,
            'wrk_point_type' => WorkingTime::UPDATE_TYPE,
            'wrk_start' => $this->workingTime()
        ];
        $this->setSession('wrk_ses_'.$id, $wrk_ses);
        
        return $this->redirect(['update', 'id'=> $id]);
    }

    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);
        
        $get_sesssion = $this->getSession('wrk_ses_'.$id);
        if ($get_sesssion == '') {
            return $this->redirect(['to-update','id'=> $id]);
        }
        // validation has reviewed
        $superuser = Yii::$app->user->identity->superuser;
        if ($model->sna_status != 0 && $superuser != 1) {
            $this->checkSession($id);
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this page.'));
        } elseif ($model->sna_status != 0 && $superuser = 1) {
            $this->checkSession($id);
            return $this->redirect(['correction/to-correction?id='.$id]);
        }

        $model->scenario = 'update';

        // 'staging condition only'
        // there might be data that has no member (member not set)
        $ctr = 'ID';

        if($model->member) {
            $ctr = $model->member->acc_cty_id;
        }

        // ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))  {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        // get post request form
        if ($model->load(Yii::$app->request->post())) {
            $model->sna_transaction_time = $_POST['d1'] . ' ' . $_POST['t1'];
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->sna_transaction_time = Utc::getTime($model->sna_transaction_time);
                $model->sna_point = floor($model->sna_receipt_amount);

                $model->sna_review_date = Utc::getNow();
                $model->sna_review_by = Yii::$app->user->id;
                $model->sna_company_tagging = 1;

                // limited transaction point per-user per-merchant per-day
                if ($model->sna_transaction_time != 0) {
                    $t = $model->sna_transaction_time;
                    $u = $model->sna_acc_id;
                    $c = $model->sna_com_id;
                    $sna_status = $this->approvedReceiptPerday($t,$u,$c);
                    if ($sna_status == 2) {
                        $model->sna_status = $sna_status;
                        $model->sna_sem_id = SnapEarnRemark::FORCE_REJECTED_MAX_PER_DAY;
                    }
                }
                // if approved action
                if ($model->sna_status == 1) {

                    // get limited point per country
                    $config = SnapEarnRule::find()->where(['ser_country' => $model->member->country->cty_currency_name_iso3])->one();

                    // setup devision point per country
                    if ($config->ser_point_provision > 0 ) {
                        $model->sna_point = (int) ((int)$model->sna_receipt_amount / $config->ser_point_provision);
                    }

                    // optional point for premium or default merchant
                    if(!empty($config) && !empty($model->business)) {
                        if($model->business->com_premium == 1) {
                            $model->sna_point *= 2;
                            $limitPoint = $config->ser_premium;
                        } else {
                            $limitPoint = $config->ser_point_cap;
                        }
                    }

                    // get current point merchant
                    $merchant_point = Company::find()->getCurrentPoint($model->sna_com_id);
                    $point_history = LoyaltyPointHistory::find()->getCurrentPoint($model->sna_acc_id);
                    if ($point_history !== NULL) {
                        $current_point = $point_history['lph_total_point'];
                    } else {
                        $current_point = 0;
                    }

                    if ($model->sna_point > $limitPoint) {
                        $model->sna_point = $limitPoint;
                    }
                    $model->sna_sem_id = '';
                    // if rejected action
                } elseif ($model->sna_status == 2) {
                    $username = $model->member->acc_screen_name;
                    $email = $model->member->acc_facebook_email;
                    $model->sna_transaction_time = NULL;
                    $model->sna_point = 0;
                } else {
                    $model->sna_review_date = 0;
                    $model->sna_review_by = 0;
                    $model->sna_transaction_time = NULL;
                    $model->sna_point = 0;
                    $model->sna_sem_id = 0;
                }

                // execution save to snapearn
                $snap_type = '';
                if ($model->save()) {
                    if ($model->sna_status == 1) {
                        $params = [
                            'current_point' => $current_point,
                            'sna_point' => $model->sna_point,
                            'sna_acc_id' => $model->sna_acc_id,
                            'sna_com_id' => $model->sna_com_id,
                            'sna_id' => $model->sna_id,
                            'desc' => 'Credit from Approved',
                        ];
                        $merchantParams = [
                            'com_point' => $merchant_point->com_point,
                            'sna_point' => $model->sna_point,
                            'sna_com_id' => $model->sna_com_id,
                        ];
                        $history = $this->savePoint($params);
                        $point = $this->merchantPoint($merchantParams, false);

                        if ($model->sna_push == 1) {
                            $params = [$model->sna_acc_id, $model->sna_id, $model->sna_com_id, $_SERVER['REMOTE_ADDR']];
                            $customData = ['type' => 'snapearn'];
                            Activity::insertAct($model->sna_acc_id, 31, $params, $customData);
                        }

                        // create snapearn point detail
                        $this->setMessage('save', 'success', 'Snap and Earn successfully approved!');
                        $snap_type = 'approved';
                    } elseif ($model->sna_status == 2) {
                        // send email to member
                        $business = '';
                        $location = '';
                        if (empty($model->sna_com_id)) {
                            $business = $model->newSuggestion->cos_name;
                            $location = $model->newSuggestion->cos_location;
                        } else {
                            $business = Company::findOne($model->sna_com_id)->com_name;
                            $location = Company::findOne($model->sna_com_id)->com_address;
                        }

                        if (!empty($model->sna_sem_id)) {
                            $params = [];
                            $picture = Yii::$app->params['businessUrl'] . 'receipt/receipt_sample.jpg';

                            switch ($model->sna_sem_id) {
                                case 1:
                                    $params[0] = ['[username]', $username];
                                    $params[1] = ['[picture]', $picture];
                                    break;
                                case 2:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[picture]', $picture];
                                    break;
                                case 3:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[picture]', $picture];
                                    break;
                                case 4:
                                    $params[0] = ['[username]', $username];
                                    $params[1] = ['[business]', $business];
                                    break;
                                case 5:
                                    $params[] = ['[username]', $username];
                                    break;
                                case 6:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    break;
                                case 7:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    $params[] = ['[location]', $location];
                                    break;
                                case 8:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    break;
                                case 9:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    break;
                                case 10:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    $params[] = ['[location]', $location];
                                    break;
                                case 11:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    break;
                                case 12:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    break;
                                case 13:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    break;
                                case 14:
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
                                    break;
                            }
                            // hide sementara
                            // Yii::$app
                            //     ->AdminMail
                            //     ->backend($model->member->acc_facebook_email, $params)
                            //     ->snapearnRejected($model->sna_sem_id)
                            //     ->send()
                            //     ->view();
                        }

                        //if push notification checked then send to activity
                        if ($model->sna_push == 1) {
                            $params = [$model->sna_acc_id, $model->sna_com_id, $_SERVER['REMOTE_ADDR']];
                            $customData = ['type' => 'snapearn'];
                            Activity::insertAct($model->sna_acc_id, 30, $params, $customData);
                        }
                        // create snapearn point detail
                        $this->setMessage('save', 'success', 'Snap and Earn successfully rejected!');
                        $snap_type = 'rejected';
                    }

                    // webhook for manis v3
                    // https://apixv3.ebizu.com/v1/admin/after/approval?data={"acc_id":1,"sna_id":1,"sna_status":1}
                    $curl = new curl\Curl();
                    $curl->get(Yii::$app->params['WEBHOOK_MANIS_API'].'?data={"acc_id":' . intval($model->sna_acc_id) . ',"sna_id":' . intval($model->sna_id) . ',"sna_status":' . intval($model->sna_status) . '}');

                    // $audit = AuditReport::setAuditReport('update snapearn (' . $snap_type . ') : ' . $model->member->acc_facebook_email.' upload on '.Yii::$app->formatter->asDate($model->sna_upload_date), Yii::$app->user->id, SnapEarn::className(), $model->sna_id)->save();

                    $activities = [
                        'Snap Earn',
                        'Snapearn ' . ($model->sna_status == 1 ? 'APPROVED' : 'REJECTED') . ' in ' . $model->member->acc_facebook_email . ' on ' . $model->business->com_name,
                        SnapEarn::className(),
                        $model->sna_id
                    ];
                    $this->saveLog($activities);

                    // end working time
                    $last_working_ses = $this->getSession('wrk_ses_'.$id);
                    if (!empty($last_working_ses)) {
                        $ses = [];
                        $type = ($model->sna_sem_id != '') ? $model->sna_sem_id : WorkingTime::APP_TYPE;
                        $ses['wrk_end'] = $this->workingTime($id);
                        $ses['wrk_description'] = $this->getPoint($type)->spo_name;
                        $ses['wrk_type'] = $model->sna_status;
                        $ses['wrk_rjct_number'] = $type;
                        $ses['wrk_point'] = $this->getPoint($type)->spo_point;
                        $last_ses = array_merge($last_working_ses,$ses);
                        $this->setSession('wrk_ses_'.$id, $last_ses);
                        $this->saveWorking($id);
                    }
                    
                    $this->checkSession($id);
                    $transaction->commit();
                    
                } else {
                    $transaction->rollBack();
                    $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }

            if ($_POST['saveNext'] == 1) {
                $nextUrl = SnapEarn::find()->saveNext($id, $ctr);
                if (!empty($nextUrl))
                    return $this->redirect(['default/to-update?id=' . $nextUrl->sna_id]);
            }
            if (!empty(Url::previous())) {
                return $this->redirect(Url::previous());
            } else {
                return $this->redirect(['/snapearn']);
            }
        } else {
            $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
        }
                                
        $model->sna_transaction_time = Utc::convert($model->sna_upload_date);
        $model->sna_upload_date = Utc::convert($model->sna_upload_date);

        return $this->render(Yii::$app->permission_helper->setRbacView('form','form_rbac'), [
            'model' => $model,
            'id' => $id
        ]);
    }

    public function actionShortPoint($id,$sna_id, $type = null)
    {
        $model = Company::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(false)) {
                $activities = [
                    'Merchant',
                    'Merchant (' . $model->com_name . ') - Add (' . $model->com_point . ') Point',
                    Company::className(),
                    $model->com_id
                ];
                $this->saveLog($activities);
                if ($type == 2) {
                    return $this->redirect(['correction/correction?id='.$sna_id]);
                }
                return $this->redirect(['update?id='.$sna_id]);
            }
        }
        return $this->renderAjax('point',[
            'model' => $model
        ]);
    }

    public function actionAjaxSnapearnPoint()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $amount = Yii::$app->request->post('amount');
            $com_id = Yii::$app->request->post('com_id');
            $business = Company::findOne($com_id);
            $se = $this->findModel($id);

            $config = SnapEarnRule::find()->where(['ser_country' => $se->member->country->cty_currency_name_iso3])->one();

            $point = $amount;

            if ($config->ser_point_provision > 0 ) {
                $point = (int) ($amount / $config->ser_point_provision);
            }

            if (!empty($business)) {
                if(!empty($config)) {
                    if($business->com_premium == 1) {
                        $point *= 2;
                        $point_cap = $config->ser_premium;
                    } else {
                        $point_cap = $config->ser_point_cap;
                    }

                    if($point > $point_cap)
                        return $point_cap;
                }
                return $point;
            } else {
                return "empty-b";
            }
        }
    }

    public function actionCancel($id)
    {
        $this->checkSession($id);
    }
    
    protected function checkSession($id)
    {
        $this->removeSession('wrk_ses_'.$id);
    }

    protected function findModel($id)
    {
    	if (($model = SnapEarn::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function approvedReceiptPerday($t,$u,$c)
    {
        $model = SnapEarn::find()->maxDuplicateReceipt($t,$u,$c);
        $sna_status = 0;
        if ($model->count() >= 2) {
            $sna_status = 2;
        }
        return $sna_status;
    }

    protected function savePoint($params, $type = 'C')
    {
        $valid = 365;
        $time = time();
        if($type == 'C')
            $total_point = $params['current_point'] + $params['sna_point'];
        else
            $total_point = $params['current_point'] - $params['sna_point'];
        $history = new LoyaltyPointHistory();
        $history->setScenario('snapEarnUpdate');
        $history->lph_acc_id = $params['sna_acc_id'];
        $history->lph_com_id = $params['sna_com_id'];
        $history->lph_lpt_id = 56;
        $history->lph_amount = $params['sna_point'];
        $history->lph_param =  (string)$params['sna_id'];
        $history->lph_type = $type;
        $history->lph_datetime = $time;
        $history->lph_total_point = $total_point;
        $history->lph_expired = $time + $valid * 86400;
        $history->lph_current_point = $params['sna_point'];
        $history->lph_description = $params['desc'];
        if($history->save()) {
            $activities = [
                'Point History',
                'Point ' . $history->lph_total_point . ' (' . $history->lph_type . ') has been added to ' . $history->merchant->com_email,
                LoyaltyPointHistory::className(),
                $history->lph_param
            ];
            $this->saveLog($activities);

            $this->setMessage('save', 'error', General::extractErrorModel($history->getErrors()));
        }
    }

    protected function merchantPoint($params, $type = true)
    {
        // update merchant point
        if($type == true)
            $com_point = $params['com_point'] + $params['sna_point'];
        else
            $com_point = $params['com_point'] - $params['sna_point'];
        $point = Company::findOne($params['sna_com_id']);
        $point->setScenario('snapEarnUpdate');
        $pointBefore = $point->com_point;
        $point->com_point = $com_point;
        if($point->save(false)) {
            $activities = [
                'Change Point',
                'Merchant Point (' . $pointBefore . ') changed to ' . $point->com_point . ' has been added to ' . $point->com_email,
                Company::className(),
                $point->com_id
            ];
            $this->saveLog($activities);

            $this->setMessage('save', 'error', General::extractErrorModel($point->getErrors()));
        }
    }

    protected function assignFcs($fsc, $com_id, $company, $fes_code)
    {
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

    protected function assignModule($com_id, $company)
    {
        $modulInstall = Module::find()->where('mod_id IN (12,19,15)')->All();
        foreach ($modulInstall as $module)
        {
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

    protected function assignEmail($com_id, $company)
    {
        $systemMessage = new SystemMessage;
        $typeMessage = 17;
        $categoryMessage = 'business_signup';
        $email = $company->com_email;
        $com_name = $company->com_name;
        $parsersData[] = array('[name]', htmlspecialchars_decode($com_name, ENT_QUOTES));
        $systemMessage->Parser($typeMessage, $categoryMessage, $email, $parsersData);
    }
    
    public function actionUserList()
    {
    	if (Yii::$app->request->isAjax) {
    		$model = User::find()->findUser();
            $out = [];
            foreach ($model as $d) {
                $out[] = ['id' => $d->id,'value' => $d->username];
            }
            echo \yii\helpers\Json::encode($out);
    	}
    }

    public function actionList()
    {
        if (Yii::$app->request->isAjax) {
            $model = Company::find()->searchExistingMerchant();
            $out = [];
            if (!empty($model)) {
                foreach ($model as $d) {
                    $out[] = [
                        'id' => $d->com_id,
                        'value' => $d->com_name
                    ];
                }
            } else {
                $out[] = [
                    'id' => 0,
                    'value' => 'Merchant Not Found!',
                ];
            }

            echo \yii\helpers\Json::encode($out);
        }
    }

    public function actionSearchMemberEmail()
    {
        if (Yii::$app->request->isAjax) {
            $model = Account::find()->searchMemberEmail();
            $out = [];
            if (!empty($model)) {
                foreach ($model as $d) {
                    $out[] = [
                        'id' => $d->acc_facebook_email,
                        'value' => $d->acc_facebook_email
                    ];
                }
            } else {
                $out[] = [
                    'id' => 0,
                    'value' => 'Email Not Found!',
                ];
            }

            echo \yii\helpers\Json::encode($out);
        }
    }

    public function actionCityList()
    {
        if (Yii::$app->request->isAjax){
            $model = City::find()->SearchCityList();
            echo \yii\helpers\Json::encode($model);
        }
    }

    public function actionMallList($q = null, $id = null)
    {
        if (Yii::$app->request->isAjax){
            $model = Mall::find()->SearchMallList();
            $out = [];
            if (!empty($model)) {
                foreach ($model as $d) {
                    $out[] = ['id' => $d->mal_id,'value' => $d->mal_name];
                }
            }else{
                $out[] = ['id' => 0,'value' => 'Mall Not Found!'];
            }
            echo \yii\helpers\Json::encode($out);
        }
    }
    
    protected function getPoint($id)
    {
        $model = SnapearnPoint::findOne($id);
        return $model;
    }

    protected function getCategoryId($id)
    {
        
        $parent_id = \app\models\CompanyCategory::findOne($id)->com_parent_category_id;
        return $parent_id;
        
//        $cats = [
//            1 => [1, 7, 8, 9, 10, 58, 122, 125],
//            2 => [24, 121],
//            3 => [4, 23, 34, 36, 37],
//            4 => [13, 16, 43, 59, 120],
//            5 => [18, 38, 61],
//            6 => [15, 20, 41, 62, 63, 64, 119],
//            7 => [3, 14, 25, 26, 27, 31, 32, 33],
//            8 => [5, 39, 50, 60],
//            9 => [6, 12, 17, 19, 21],
//            10 => [28, 30],
//            11 => [35, 42],
//            12 => [2, 11, 22, 44, 123, 124, 126]
//        ];
//        foreach ($cats as $key => $val) {
//            $check = in_array($id,$val);
//            if ($check) {
//                return $key;
//            }
//        }
//        return null;
        // dari bias
        // $maps = array(
        //     array(1, 7, 8, 9, 10, 58, 122),
        //     array(24, 121),
        //     array(4, 23, 34, 36, 37),
        //     array(13, 16, 43, 59, 120),
        //     array(18, 38, 61),
        //     array(15, 20, 41, 62, 63, 64, 119),
        //     array(3, 14, 25, 26, 27, 31, 32, 33),
        //     array(5, 39, 50, 60),
        //     array(6, 12, 17, 19, 21),
        //     array(28, 30),
        //     array(35, 42),
        //     array(2, 11, 22, 44, 123),
        // );

    }

    public function actionTest()
    {
        $model = SnapEarn::find()
            ->joinWith(['merchant' => function($query) {
                   return $query->from('ebizu_db.'.Company::tableName())
                          ->andWhere(['com_id' => 1642497]);
            }])
            ->one();
        var_dump($model);exit;
    }

}
