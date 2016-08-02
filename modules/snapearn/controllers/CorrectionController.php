<?php

namespace app\modules\snapearn\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use linslin\yii2\curl;
use app\controllers\BaseController;
use app\components\helpers\Utc;
use app\components\helpers\General;
use app\models\Account;
use app\models\City;
use app\models\Mall;
use app\models\SnapEarn;
use app\models\SnapEarnRule;
use app\models\Company;
use app\models\Activity;
use app\models\LoyaltyPointHistory;
use app\models\MerchantUser;
use app\models\CompanySuggestion;
use app\models\SnapEarnPointDetail;
use app\models\FeatureSubscription;
use app\models\FeatureSubscriptionCompany;
use app\models\AuditReport;
use app\models\Module;
use app\models\ModuleInstalled;
use app\models\SystemMessage;
use app\models\WorkingTime;
use yii\web\HttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Session;

/**
*
*/
class CorrectionController extends BaseController
{
    public function actionToCorrection($id)
    {
        // destroy session com && create session company id
        $this->removeSession('oldCompany_'.$id);
        $this->removeSession('ses_com_'.$id);
        $this->removeSession('wrk_ses_'.$id);
        
        // working time start session
        $wrk_ses = [
            'wrk_by' => Yii::$app->user->id,
            'wrk_param_id' => $id,
            'wrk_point_type' => WorkingTime::CORRECTION_TYPE,
            'wrk_start' => $this->workingTime()
        ];
        $this->setSession('wrk_ses_'.$id, $wrk_ses);
        
        if (empty($this->getSession('oldCompany_'.$id))) {
            $model = $this->findModel($id);
            $mp = Company::find()->getCurrentPoint($model->sna_com_id);
            $params = [
                'sna_id' => $id,
                'com_id' => $model->sna_com_id,
                'sna_point' => $model->sna_point,
                'com_point' => $mp->com_point,
                'ops_id' => \Yii::$app->user->id,
            ];
            $this->setSession('oldCompany_'.$id,$params);
        }
        return $this->redirect(['correction', 'id' => $id]);
    }

	public function actionCorrection($id)
	{
    	$model = $this->findModel($id);
    	$model->scenario = 'correction';
       
        // get com id from session
        $old = $this->getSession('oldCompany_'.$id);
        $ses_com = $this->getSession('ses_com_'.$id);
        $get_sesssion = $this->getSession('wrk_ses_'.$id);
        
        if (empty($old) || empty($get_sesssion)) {
            return $this->redirect(['to-correction','id'=> $id]);
        }

        // validation superuser
        $superuser = Yii::$app->user->identity->superuser;
        if ($model->sna_status == 0 && $superuser != 1) {
            $this->cancelWorking($id);
            return $this->redirect(['default/to-update','id'=> $id]);
        }elseif ($model->sna_status != 0 && $superuser != 1) {
            $this->cancelWorking($id);
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this page.'));
        }
                                
        // get old point
        $ctr = $model->member->acc_cty_id;

        // ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))  {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        // get post request form
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->sna_transaction_time = Utc::getTime($model->sna_transaction_time);
                $model->sna_point = floor($model->sna_receipt_amount);

                $model->sna_review_date = Utc::getNow();
                $model->sna_review_by = Yii::$app->user->id;

				// start process rollback
                // configuration to get real point user before reviews
                $mp = Company::find()->getCurrentPoint($model->sna_com_id);
                $up = LoyaltyPointHistory::find()->getCurrentPoint($model->sna_acc_id);
                if($up !== NULL) {
                    $cp = $up->lph_total_point;
                } else {
                    $cp = 0;
                }
               
                // check session new merchant
                if (!empty($ses_com)) {
                    $model->sna_com_id = $ses_com['sna_com_id'];
                    $model->sna_cat_id = $ses_com['sna_cat_id'];
                    $model->sna_com_name = $ses_com['sna_com_name'];
                }
                
                $minusPointUser = [
                    'current_point' => $cp,
                    'sna_point' => $old['sna_point'],
                    'sna_acc_id' => $model->sna_acc_id,
                    'sna_com_id' => $old['com_id'],
                    'sna_id' => $model->sna_id,
                    'desc' => 'Debet from Correction',
                ];
                $history = $this->savePoint($minusPointUser,'D');
                
                // param to configuration to give back point merchant
                $addPointmerchant = [
                    'com_point' => $old['com_point'],
                    'sna_point' => $old['sna_point'],
                    'sna_com_id' => $old['com_id'],
                ];
                $point = $this->merchantPoint($addPointmerchant);
                // end process rollback

                // get current point merchant
                $merchant_point = Company::find()->getCurrentPoint($model->sna_com_id);
                $point_history = LoyaltyPointHistory::find()->getCurrentPoint($model->sna_acc_id);
                if ($point_history !== NULL) {
                    $current_point = $point_history->lph_total_point;
                } else {
                    $current_point = 0;
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

                    if ($model->sna_point > $limitPoint) {
                        $model->sna_point = $limitPoint;
                    }
                    $model->sna_sem_id = '';
                    // if rejected action
                } elseif ($model->sna_status == 2) {
                    $username = $model->member->acc_screen_name;
                    $email = $model->member->acc_facebook_email;
                    $model->sna_point = 0;
                    // $model->sna_receipt_amount = 0;
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
                            'desc' => 'Credit from Correction',
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
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[picture]', $picture];
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
                                    $params[] = ['[username]', $username];
                                    $params[] = ['[business]', $business];
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
                            }

                            Yii::$app
                                ->AdminMail
                                ->backend($model->member->acc_facebook_email, $params)
                                ->snapearnRejected($model->sna_sem_id)
                                ->send()
                                ->view();
                        }

                        //if push notification checked then send to activity
                        if ($model->sna_push == 1) {
                            $params = [$model->sna_acc_id, $model->sna_com_id, $_SERVER['REMOTE_ADDR']];
                            $customData = ['type' => 'snapearn'];
                            Activity::insertAct($model->sna_acc_id, 30, $params, $customData);
                        }

                        // create snapearn point detail
                        // SnapEarnPointDetail::savePoint($id, $model->sna_sem_id);
                        $this->setMessage('save', 'success', 'Snap and Earn successfully rejected!');
                        $snap_type = 'rejected';
                    }

                    // webhook for manis v3
                    // https://apixv3.ebizu.com/v1/admin/after/approval?data={"acc_id":1,"sna_id":1,"sna_status":1}
                    $curl = new curl\Curl();
                    $curl->get(Yii::$app->params['WEBHOOK_MANIS_API'].'?data={"acc_id":' . intval($model->sna_acc_id) . ',"sna_id":' . intval($model->sna_id) . ',"sna_status":' . intval($model->sna_status) . '}');
                    
                    $audit = AuditReport::setAuditReport('update snapearn (' . $snap_type . ') : ' . $model->member->acc_facebook_email.' upload on '.Yii::$app->formatter->asDate($model->sna_upload_date), Yii::$app->user->id, SnapEarn::className(), $model->sna_id)->save();

                    // end working time
                    $last_working_ses = $this->getSession('wrk_ses_'.$id);
                    $ses_wrk_point = (!empty($last_working_ses['wrk_point'])) ? ($last_working_ses['wrk_point'] + WorkingTime::POINT_APPROVAL) : WorkingTime::POINT_APPROVAL;
                    if (!empty($last_working_ses)) {
                        $ses = [];
                        $ses['wrk_end'] = $this->workingTime($id);
                        $ses['wrk_description'] = "Correction $snap_type";
                        $ses['wrk_type'] = $model->sna_status;
                        $ses['wrk_rjct_number'] = ($model->sna_sem_id != '') ? $model->sna_sem_id : 0;
                        $ses['wrk_point'] = $ses_wrk_point;
                        $last_ses = array_merge($last_working_ses,$ses);
                        $this->setSession('wrk_ses_'.$id, $last_ses);
                        if ($this->saveWorking($id)) {
                            // destroy session
                            $this->removeSession('oldCompany_'.$id);
                            $this->removeSession('ses_com_'.$id);
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                            $this->setMessage('save', 'error', General::extractErrorModel($this->saveWorking($id)));
                        }
                    } else {
                        $transaction->rollBack();
                        $this->setMessage('save', 'error','Session working hour not set');
                    }
                } else {
                    $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }

            if ($_POST['saveNext'] == 1) {
                $nextUrl = SnapEarn::find()->saveNext($id, $ctr);
                if (!empty($nextUrl))
                    return $this->redirect(['correction/to-correction?id=' . $nextUrl->sna_id]);
            }
            
            if (!empty($this->getRememberUrl())) {
                return $this->redirect(Url::to($this->getRememberUrl()));
            } else {
                return $this->redirect(['/snapearn']);
            }
            
        } else {
            $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
        }

        $model->sna_transaction_time = Utc::convert($model->sna_upload_date);
        $model->sna_upload_date = Utc::convert($model->sna_upload_date);
        if (!empty($ses_com)) {
            $model->sna_com_id = $ses_com['sna_com_id'];
        }
        
        return $this->render('form', [
            'model' => $model,
            'id' => $id
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

            if(!empty($config) && !empty($business)) {
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
        }
    }


    protected function findModel($id)
    {
    	if (($model = SnapEarn::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
        if($history->save())
            $this->setMessage('save', 'error', General::extractErrorModel($history->getErrors()));
    }

    public function actionCancel($id)
    {
        $chek_sesion = $this->getSession('wrk_ses_'.$id);
        if (!empty($chek_sesion['wrk_point']) && $chek_sesion['wrk_point'] == 3) {
            $this->saveWorking($id);
        }
        $this->removeSession('wrk_ses_'.$id);
        $this->removeSession('oldCompany_'.$id);
        $this->removeSession('ses_com_'.$id);
        
        if (!empty($this->getRememberUrl())) {
            return $this->redirect(Url::to($this->getRememberUrl()));
        } else {
            return $this->redirect(['/snapearn']);
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
        $point->com_point = $com_point;
        if($point->save(false))
            $this->setMessage('save', 'error', General::extractErrorModel($point->getErrors()));
    }

}
