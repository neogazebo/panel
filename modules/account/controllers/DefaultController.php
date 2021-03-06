<?php
namespace app\modules\account\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\BaseController;
use app\components\helpers\General;
use app\models\Account;
use app\models\AccountSearch;
use app\models\SnapEarn;
use app\models\LoyaltyPointHistory;
use app\models\DealIssued;
use app\models\CashvoucherRedeemed;
use app\models\RedemptionReference;
use app\models\SavedOffers;
use app\models\SavedRewards;
use app\models\Country;
use app\models\SnapEarnRule;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use linslin\yii2\curl;

/**
 * IndexController implements the CRUD actions for Account model.
 */
class DefaultController extends BaseController
{
    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Account model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $receipt = SnapEarn::find()
            ->where('sna_acc_id = :id', [':id' => $id])
            ->orderBy('sna_id DESC');
        $receiptProvider =  new ActiveDataProvider([
            'query' => $receipt,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $historyOffer = DealIssued::find()
            ->where('des_receiver_id = :id AND des_redeem_datetime > 0', [':id' => $id])
            ->orderBy('des_id DESC');
        $historyOfferProvider =  new ActiveDataProvider([
            'query' => $historyOffer,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $historyCash = CashvoucherRedeemed::find()
            ->where('cvr_acc_id = :id', [':id' => $id])
            ->orderBy('cvr_id DESC');
        $historyCashProvider =  new ActiveDataProvider([
            'query' => $historyCash,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $reference = RedemptionReference::find()
            ->where('rdr_acc_id = :id', [':id' => $id])
            ->orderBy('rdr_id DESC');
        $referenceProvider =  new ActiveDataProvider([
            'query' => $reference,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $redeem = LoyaltyPointHistory::find()
            ->where('
                lph_acc_id = :id 
                AND lph_type = :type
            ', [
                ':id' => $id,
                ':type' => 'D'
            ])
            ->orderBy('lph_id DESC');
        $redeemProvider =  new ActiveDataProvider([
            'query' => $redeem,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $offer = SavedOffers::find()
            ->where('svo_acc_id = :id', [':id' => $id])
            ->orderBy('svo_id DESC');
        $offerProvider =  new ActiveDataProvider([
            'query' => $offer,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $reward = SavedRewards::find()
            ->where('svr_acc_id = :id', [':id' => $id])
            ->orderBy('svr_id DESC');
        $rewardProvider =  new ActiveDataProvider([
            'query' => $reward,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        $member_point_history = LoyaltyPointHistory::find()->getMemberPointHistory($id);

        $pointHistoryProvider =  new ActiveDataProvider([
            'query' => $member_point_history,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'receiptProvider' => $receiptProvider,
            'historyOfferProvider' => $historyOfferProvider,
            'historyCashProvider' => $historyCashProvider,
            'referenceProvider' => $referenceProvider,
            'redeemProvider' => $redeemProvider,
            'offerProvider' => $offerProvider,
            'rewardProvider' => $rewardProvider,
            'pointHistoryProvider' => $pointHistoryProvider,
        ]);
    }

    public function actionCorrection($id)
    {
        $model = LoyaltyPointHistory::find()
            ->where('lph_acc_id = :acc_id', [
                ':acc_id' => $id
            ])
            ->orderBy('lph_id DESC')
            ->one();
        if(empty($model)) {
            $model = new LoyaltyPointHistory();
            $model->lph_acc_id = $id;
        }
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $history = new LoyaltyPointHistory();
            $history->attributes = $model->attributes;
            if($history->lph_amount > 0) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $set_time = \app\components\helpers\Utc::getNow();
                    if($history->lph_type == 'C')
                        $total_point = $model->lph_total_point + $history->lph_amount;
                    else
                        $total_point = $model->lph_total_point - $history->lph_amount;
                    $history->lph_parent = 0;
                    $history->lph_com_id = 0;
                    $history->lph_cus_id = 0;
                    $history->lph_lpe_id = 22;
                    $history->lph_param = (string)Yii::$app->user->id;
                    $history->lph_free = 'N';
                    $history->lph_datetime = $set_time;
                    $history->lph_approve = 0;
                    $history->lph_lpt_id = 0;
                    $history->lph_total_point = $total_point;
                    $history->lph_expired = $set_time + 365 * 86400;
                    $history->lph_current_point = $history->lph_amount;

                    if($history->save()) {
                        $acc_screen_name = !empty($model->member) ? $model->member->acc_screen_name . ' (' . $model->member->acc_facebook_email . ')' : $model->lph_acc_id;

                        $activities = [
                            'Account',
                            'Account - Correction Point, ' . $history->member->acc_screen_name . ' [' . $history->member->acc_facebook_email . '] by ' . $history->user->username . ' about ' . $history->lph_current_point . ' (' . $history->lph_type . ') description is "' . $history->lph_description . '" on ' . Yii::$app->formatter->asDatetime($history->lph_datetime) . ', total point is ' . $history->lph_total_point,
                            LoyaltyPointHistory::className(),
                            $id
                        ];
                        $this->saveLog($activities);

                        $transaction->commit();
                        
                        // CLEAR CACHE WEBHOOK
                        $curl = new curl\Curl();
                        $curl->get(Yii::$app->params['WEBHOOK_POINT_CORRECTION'].'?data={"acc_id":' . intval($id) . ',"point":' . intval(0) . '}');
                        
                        $this->setMessage('save', 'success', 'Point successfully corrected!');
                        return $this->redirect(['default/view?id='.$model->lph_acc_id]);
                    } else {
                        $transaction->rollBack();
                        $this->setMessage('save', 'error', General::extractErrorModel($history->getErrors()));
                        return $this->redirect(['default/view?id='.$model->lph_acc_id]);
                    }
                } catch(Expression $e) {
                    $transaction->rollBack();
                    $this->setMessage('save', 'error', General::extractErrorModel($history->getErrors()));
                    return $this->redirect(['default/view?id='.$model->lph_acc_id]);
                }
            } else {
                $this->setMessage('save', 'error', 'Point cannot be zero or less than zero!');
                return $this->redirect(['default/view?id=' . $model->lph_acc_id]);
            }
        }
        return $this->renderAjax('correction', [
            'model' => $model
        ]);
    }

    public function actionBlockUser()
    {
        if (Yii::$app->request->isAjax){
            $id = Yii::$app->request->get('id');
            $blocked_date = Yii::$app->formatter->asTimestamp(date('Y-m-d h:i:s'));
            $model = $this->findModel($id);
            $model->acc_status = ($model->acc_status == 0) ? 1 : 0;
            $model->acc_blocked_by = Yii::$app->user->id;
            $model->acc_blocked_date = $blocked_date;
            if ($model->save(false)) {
                $status = $model->acc_status == 1 ? 'activated' : 'blocked';
                $activities = [
                    'Account',
                    'Account - Block User, ' . $model->acc_facebook_email . ' on ' . $model->acc_screen_name . ' has been ' . $status . '!',
                    Account::className(),
                    $model->acc_id
                ];
                $this->saveLog($activities);

                // CLEAR CACHE WEBHOOK USER
                $curl = new curl\Curl();
                $curl->get(Yii::$app->params['WEBHOOK_BLOCK_USER'].'?data={"id":' . intval($id) . '}');
                return $this->redirect(['default/view?id='.$id]);
            }
        }
    }

    /**
    *
    *
    */
    public function actionTopChart()
    {
        if (Yii::$app->request->isAjax) {
            $filters = Yii::$app->request->post('data');
            $model = SnapEarn::find()->setChartTopFour($filters);
            $out = [];
            if (!empty($model)) {
                $i = 0;
                $color[0] = '#f56954';
                $color[1] = '#f39c12';
                $color[2] = '#3c8dbc';
                $color[3] = '#d2d6de';
                $total_amount = 0;
                foreach ($model as $d) {
                    if ($d->categoryName == null) {
                        $d->categoryName = 'Others';
                    }

                    $cr = Country::find()->where('cty_short_code = :cty', [':cty' => strtoupper($d->country)])->one();
                    $config = SnapEarnRule::find()->where(['ser_country' => $cr->cty_currency_name_iso3])->one();
                    $amount = $d->amount;
                    $k = '';
                    if ($config->ser_point_provision > 0 ) {
                        $amount = (int)($amount / $config->ser_point_provision);
                        $k = ' K';
                    }

                    $currency = ($cr->cty_currency_name_iso3 == 'IDR') ? 'Rp' : 'RM';
                    $out[] = [
                        'id' => $i,
                        'value' => $amount,
                        'color' => $color[$i],
                        'highlight' => $color[$i],
                        'label' => $d->categoryName,
                        'currency' => $currency,
                        'amount' => Yii::$app->formatter->asDecimal($amount,2),
                        'k' => $k,
                        'total' => Yii::$app->formatter->asDecimal($total_amount += $amount,2)
                    ];
                    $i++;
                }
            } else {
                $out[] = ['value' => 0,'label' => 'No Receipt','total' => 0];
            }
            echo \yii\helpers\Json::encode($out);
        }
    }
    
    public function actionChangeCountry($param) 
    {
        $model = $this->findModel($param);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(false)) {
                $activities = [
                    'Account',
                    'Account - Change Country, (' . $model->acc_facebook_email . ') ' . $model->acc_screen_name . ' change country to ' . $model->acc_cty_id,
                    Account::className(),
                    $model->acc_id
                ];
                $this->saveLog($activities);

                return $this->redirect(['view', 'id' => $param]);
            }
        }
        return $this->renderAjax('country', ['model' => $model]);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetMemberFilteredHistory()
    {
        $op_type = Yii::$app->request->post('op_type');
        $member_id = Yii::$app->request->post('member_id');
        $daterange = Yii::$app->request->post('daterange');
        $offset = Yii::$app->request->get('page');
        $limit = Yii::$app->request->get('per-page');
        
        $results = LoyaltyPointHistory::find()->filterMemberPointHistory($member_id, $daterange, $op_type, $offset, $limit);

        $pointAjaxHistoryProvider =  new ActiveDataProvider([
            'query' => $results,
            'sort' => false,
            'pagination' => [
                'pageSize' => $limit ? $limit : 10
            ]
        ]);

        $output = $this->renderPartial('/partials/member_point_history', [
            'pointAjaxHistoryProvider' => $pointAjaxHistoryProvider,
            'el_id' => $op_type
        ]);

        $return_json = [
            'status' => 'success', 
            'output' => $output
        ];
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $return_json;
    }

    public function actionExport()
    {
        $this->processOutputType();
        $this->processOutputSize();

        $model = Account::find()->all();

        $this->data_provider = $model;

        $columns = Account::find()->getExcelColumns();
        $column_styles = Account::find()->getExcelColumnsStyles();

        $filename = 'Members-' . date('Y-m-d-H-i-s', time()) . '.xlsx';

        $view_filename = 'index';
        $save_path = 'members';

        return $this->processOutput($view_filename, $columns, $column_styles, $save_path, $filename);
    }
}
