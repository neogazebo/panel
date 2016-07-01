<?php

namespace app\modules\snapearn\controllers;

use Yii;
use yii\web\NotFoundHttpException;
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

/**
*
*/
class CorrectionController extends BaseController
{


    public function actionToCorrection($id)
    {
        // working time start
        $user = Yii::$app->user->id;
        $param = $id;
        $point = WorkingTime::POINT_APPROVAL;
        $point_type = WorkingTime::CORRECTION_TYPE;
        $wrk_id = $this->startWorking($user,$param,$point_type,$point);
        return $this->redirect(['correction', 'id' => $id]);
    }

	public function actionCorrection($id)
	{
    	$model = $this->findModel($id);
    	$model->scenario = 'correction';

        $point_type = WorkingTime::CORRECTION_TYPE;
        $check_wrk = $this->checkingWrk($id,$point_type);
        if (empty($check_wrk) ) {
            return $this->redirect(['to-correction','id'=> $id]);
        }
        // validation superuser
        $superuser = Yii::$app->user->identity->superuser;
        if ($model->sna_status == 0 && $superuser != 1) {
            return $this->redirect(['default/to-update','id'=> $id]);
        }elseif ($model->sna_status != 0 && $superuser != 1) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this page.'));
        }

        // get old point
        $oldPoint = $model->sna_point;
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
                $minusPointUser = [
                    'current_point' => $cp,
                    'sna_point' => $oldPoint,
                    'sna_acc_id' => $model->sna_acc_id,
                    'sna_com_id' => $model->sna_com_id,
                    'sna_id' => $model->sna_id,
                    'desc' => 'Debet from Correction',
                ];
                $history = $this->savePoint($minusPointUser,'D');
                // param to configuration to give back point merchant
                $addPointmerchant = [
                    'com_point' => $mp->com_point,
                    'sna_point' => $oldPoint,
                    'sna_com_id' => $model->sna_com_id,
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

                    // get current point merchant
                    $mp = Company::find()->getCurrentPoint($model->sna_com_id);
                    $lph = LoyaltyPointHistory::find()->getCurrentPoint($model->sna_acc_id);

                    if ($lph->lph_total_point > $oldPoint) {
                        $cp = $lph->lph_total_point;
                    } elseif ($lph <= $oldPoint) {
                        $cp = $oldPoint;
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
                    $response = $curl->get(Yii::$app->params['WEBHOOK_MANIS_API'].'?data={"acc_id":' . intval($model->sna_acc_id) . ',"sna_id":' . intval($model->sna_id) . ',"sna_status":' . intval($model->sna_status) . '}');

                    $audit = AuditReport::setAuditReport('update snapearn (' . $snap_type . ') : ' . $model->member->acc_facebook_email.' upload on '.Yii::$app->formatter->asDate($model->sna_upload_date), Yii::$app->user->id, SnapEarn::className(), $model->sna_id)->save();

                    // end working time
                    $wrk = WorkingTime::find()->findWorkExist($model->sna_id)->one();
                    $desc = "Correction S&E $snap_type";
                    $type = $model->sna_status;
                    $this->endWorking($wrk->wrk_id,$type, $desc);

                    $transaction->commit();
                } else {
                    $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }

            if ($_POST['saveNext'] == 1) {
                $nextUrl = SnapEarn::find()->saveNext($id, $ctr);
                if (!empty($nextUrl))
                    return $this->redirect(['correction/to-update?id=' . $nextUrl->sna_id]);
            }
            return $this->redirect([$this->getRememberUrl()]);
        } else {
            $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
        }

        $model->sna_transaction_time = Utc::convert($model->sna_upload_date);
        $model->sna_upload_date = Utc::convert($model->sna_upload_date);
        $model->sna_receipt_amount = Yii::$app->formatter->asDecimal($model->sna_receipt_amount);

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
        $this->cancelWorking($id);
        return $this->redirect([$this->getRememberUrl()]);
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
        if($point->save())
            $this->setMessage('save', 'error', General::extractErrorModel($point->getErrors()));
    }

}
