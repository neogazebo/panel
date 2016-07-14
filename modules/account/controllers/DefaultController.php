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
use app\models\SavedOffers;
use app\models\SavedRewards;
use app\models\Country;
use app\models\SnapEarnRule;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

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
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $offer = SavedOffers::find()
            ->where('svo_acc_id = :id', [':id' => $id])
            ->orderBy('svo_id DESC');
        $offerProvider =  new ActiveDataProvider([
            'query' => $offer,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $reward = SavedRewards::find()
            ->where('svr_acc_id = :id', [':id' => $id])
            ->orderBy('svr_id DESC');
        $rewardProvider =  new ActiveDataProvider([
            'query' => $reward,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'receiptProvider' => $receiptProvider,
            'redeemProvider' => $redeemProvider,
            'offerProvider' => $offerProvider,
            'rewardProvider' => $rewardProvider,
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
                        $acc_screen_name = !empty($model->member) ? $model->member->acc_screen_name . ' (' . $model->member->acc_google_email . ')' : $model->lph_acc_id;
                        $transaction->commit();
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

    /**
    *
    *
    */
    public function actionTopChart()
    {
        if (Yii::$app->request->isAjax){
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

                    $cr = Country::find()->where('cty_short_code = :cty',[':cty' => strtoupper($d->country)])->one();
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
                        'k' => $k,
                        'total' => $total_amount += $amount
                    ];
                    $i++;
                }
            }else{
                $out[] = ['value' => 0,'label' => 'No Receipt','total' => 0];
            }
            echo \yii\helpers\Json::encode($out);
        }
    }

    /**
     * Creates a new Account model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Account();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->acc_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Account model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->acc_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Account model.
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
}
