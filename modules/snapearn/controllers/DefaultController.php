<?php

namespace app\modules\snapearn\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;
use app\components\helpers\Utc;
use app\components\helpers\General;
use app\models\Account;
use app\models\Snapearn;
use app\models\SnapEarnRule;
use app\models\Company;
use app\models\Activity;
use app\models\LoyaltyPointHistory;
use app\models\MerchantUser;

/**
 * Default controller for the `snapearn` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function init()
    {
        $timezone = date_default_timezone_get();
        date_default_timezone_set($timezone);
    }

    public function actionIndex()
    {
        $this->setRememberUrl();
        // $model = SnapEarn::find()->orderBy('sna_upload_date DESC');
        $model = SnapEarn::find()->findCustome();
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

    public function actionToUpdate($id)
    {
        // Yii::$app->workingTime->start($id);
        return $this->redirect(['update', 'id' => $id]);
    }

    public function actionNewMerchant($reg = 'EBC')
    {
        $model = new MerchantUser();
        $model->scenario = 'signup';
        $model->usr_password = md5('123456');
        $model->usr_type_id = 2;
        $model->usr_approved = 0;

        $company = new Company();

        // get merchant sugestion
        $idse = $_GET['id'];
        $sugest = SnapEarn::findOne($idse);

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

                    if ($company->save()) {
                        $com_id = $company->com_id;
                        $snapearn = SnapEarn::findOne($id);
                        $snapearn->sna_com_id = $com_id;
                        $snapearn->save(false);

                        $suggestion = CompanySuggestion::find()->where('cos_sna_id = :id', [':id' => $id])->one();
                        $suggestion->cos_com_id = $com_id;
                        $suggestion->save(false);

                        SnapEarnPointDetail::savePoint($id, 8);

                        $audit = AuditReport::setAuditReport('create business from snapearn : ' . $company->com_name, Yii::$app->user->id, Company::className(), $company->com_id);
                        if ($audit->save()) {
                            $com_id = $company->com_id;
                            // $company->setTag();
                            $fsc = new FeatureSubscriptionCompany();
                            $this->assignFcs($fsc, $com_id, $company, $company->fes_id);
                            if ($fsc->save()) {
                                $this->assignModule($com_id, $company);
                                $this->assignEmail($com_id, $company);
                                $transaction->commit();
                                $this->setMessage('save', 'success', 'Your company has been registered!');
                                return $this->render('success');
                            } else {
                                throw new HttpException(404, 'Cant insert to subscription');
                            }
                        }
                    } else {
                        $this->setMessage('save', 'error', General::extactErrorModel($model->getErrors()));
                    }
                } else {
                    $this->setMessage('save', 'error', General::extactErrorModel($model->getErrors()));
                }
            } catch (Exception $e) {
                $transaction->rollback();
                throw $e;
            }
        }



        // if ($company->load(Yii::$app->request->post())) {
        //     $transaction = Yii::$app->db->beginTransaction();

        //     $model->usr_email = $company->com_email;
        //     try {
        //         if ($model->save()) {
        //             $company->com_usr_id = $model->usr_id;
        //             $company->com_email = $model->usr_email;

        //             if ($company->save()) {
        //                 $audit = AuditReport::setAuditReport('create business : ' . $company->com_name, Yii::$app->user->id, Company::className(), $company->com_id);
        //                 if ($audit->save()) {
        //                     \Yii::$app->session->set('company', '');
        //                     $com_id = $company->com_id;
        //                     $company->setTag();
        //                     $fsc = new FeatureSubscriptionCompany();
        //                     $this->assignFcs($fsc, $com_id, $company, $company->fes_id);
        //                     if ($fsc->save()) {
        //                         $this->assignModule($com_id, $company);
        //                         $this->assignEmail($com_id, $company);
        //                         $transaction->commit();
        //                         $this->setMessage('save','success', 'Business created successfully!');
        //                         return $this->render('success');
        //                     } else {
        //                         $transaction->rollback();
        //                         $this->setMessage('save','error', 'Something wrong while subscription business. Please try again!');
        //                         return $this->redirect(['index']);
        //                     }
        //                 }else{
        //                     $transaction->rollback();
        //                     $this->setMessage('save','error', 'Something wrong while create business. Please try again!');
        //                     return $this->redirect(['index']);
        //                 }
        //             }else{
        //                 $transaction->rollback();
        //                 $this->setMessage('save','error', 'Something wrong while create business. Please try again!');
        //                 return $this->redirect(['index']);
        //             }
        //         } else {
        //             $transaction->rollback();
        //             $this->setMessage('save','error', 'Something wrong while create member. Please try again!');
        //             return $this->redirect(['index']);
        //         }
        //     } catch (Exception $ex) {
        //         $transaction->rollback();
        //         throw $e;
        //     }
        // }

        $company->com_registered_to = strtoupper($reg);
        return $this->render('new', [
            'company' => $company,
            'merchantSugest' => $sugest
        ]);


    }

    public function actionAjaxExisting($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->sna_com_id = (int)Yii::$app->request->post('com_id');
            if ($model->save()) {
                $this->setMessage('save', 'success', 'Merchant created successfully!');
                return $this->redirect(['update?id='.$id]);
            }
        } else {
            return $this->renderAjax('existing', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);

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

                $set_time = Utc::getNow();
                $set_operator = Yii::$app->user->id;
    
                $limitPoint = 0;
                if ($model->merchant->com_premium == 1) {
                    $model->sna_point = $model->sna_point * 2;
                    $limit = SnapEarnRule::find()->where('ser_country = :cny', [':cny' => $model->merchant->com_currency])->one()->ser_premium;
                    if (!empty($limit)) {
                        $limitPoint = $limit;
                    }
                } else {
                    $limit = SnapEarnRule::find()->where('ser_country = :cny', [':cny' => $model->merchant->com_currency])->one()->ser_point_cap;
                    if (!empty($limit)) {
                        $limitPoint = $limit;
                    }
                }

                $merchant_point = Company::find()->getCurrentPoint($model->sna_com_id);
                $point_history = LoyaltyPointHistory::find()->getCurrentPoint($model->sna_acc_id);
                if ($point_history !== NULL) {
                    $current_point = $point_history->lph_total_point;
                } else {
                    $current_point = 0;
                }

                // if approved action
                if ($model->sna_status == 1) {
                    if ($model->sna_point > $limitPoint) {
                        $model->sna_point = $limitPoint;
                    }
                    $model->sna_approved_datetime = $set_time;
                    $model->sna_approved_by = $set_operator;
                    $model->sna_rejected_datetime = NULL;
                    $model->sna_rejected_by = NULL;
                    $model->sna_sem_id = '';
                    // if rejected action
                } elseif ($model->sna_status == 2) {
                    $username = $model->member->acc_screen_name;
                    $email = $model->member->acc_facebook_email;
                    $model->sna_approved_datetime = NULL;
                    $model->sna_approved_by = NULL;
                    $model->sna_rejected_datetime = $set_time;
                    $model->sna_rejected_by = $set_operator;
                    $model->sna_point = 0;
                    $model->sna_receipt_amount = 0;
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
                        // SnapEarnPointDetail::savePoint($id, 7);
    
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
                    // Yii::$app->workingTime->end($id);
                } else {
                    $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
                }

                // $audit = AuditReport::setAuditReport('update snapearn (' . $snap_type . ') : ' . $model->member->mem_email.' upload on '.Yii::$app->formatter->asDate($model->sna_upload_date), Yii::$app->user->id, SnapEarn::className(), $model->sna_id)->save();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }

            if ($_POST['saveNext'] == 1) {
                $nextUrl = SnapEarn::find()->saveNext($id, $ctr);
                if (!empty($nextUrl))
                    return $this->redirect(['default/to-update?id=' . $nextUrl->sna_id]);
            }
            return $this->redirect([$this->getRememberUrl()]);
        } else {
            $this->setMessage('save', 'error', General::extractErrorModel($model->getErrors()));
        }

        $model->sna_transaction_time = date('Y-m-d H:i:s', Utc::convert($model->sna_upload_date));
        $model->sna_upload_date = date('d, M Y H:i:s', Utc::convert($model->sna_upload_date));

        return $this->render('form', [
            'model' => $model,
            'id' => $id
        ]);
    }

    public function actionAjaxSnapearnPoint()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $point = Yii::$app->request->post('point');
            $com_id = Yii::$app->request->post('com_id');
            $business = Company::findOne($com_id);

            $config = SnapEarnRule::find()->where(['ser_country' => $business->com_currency])->one();
            if(!empty($config)) {
                if($business->com_premium == 1) {
                    $point *= 2;
                    $point_cap = $config->ser_premium;
                } else
                    $point_cap = $config->ser_point_cap;
                if($point > $point_cap)
                    return $point_cap;
            }
            return $point;
        }
    }

    public function actionCancel($id)
    {
        // Yii::$app->workingTime->cancel($id); 
        return $this->redirect([$this->getRememberUrl()]);
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
        if($history->save())
            $this->setMessage('save', 'error', General::extractErrorModel($history->getErrors()));
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

    public function actionSearch()
    {
        $model = SnapEarn::find()->listFilter();
    }

    public function actionMycropper()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('mycropper');
        }
    }

}
