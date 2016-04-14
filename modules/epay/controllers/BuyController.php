<?php

namespace app\modules\epay\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\AuditReport;
use app\models\Epay;
use app\models\EpayDetail;
use app\models\Voucher;
use app\models\VoucherBought;
use app\models\VoucherBoughtDetail;
use vova07\console\ConsoleRunner;
/**
 * Description of BuyController
 *
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 * @author Ilham Fauzi <ilham@ebizu.com>
 */
class BuyController extends EpaybaseController
{
    private $_pageSize = 20;

    public function actionIndex()
    {
        $this->setRememberUrl();
        $model = Epay::find()->with(['rewardBought', 'productTitle'])->voucher(true);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => $this->_pageSize,
            ],
        ]);
//        $model->joinWith(['rewardBought' => function($model) {
//            $model->from(['rewardBought' => 'tbl_voucher_bought']);
//        }]);
//        $model->joinWith(['productTitle' => function($model) {
//            $model->from(['productTitle' => 'tbl_epay_product']);
//        }]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSell()
    {
        if(isset($_POST)) {
            $model = Epay::findOne($_POST['epa_id']);
            $transaction = Yii::$app->db->beginTransaction();
            if(!empty(Voucher::findOne($model->epa_vou_id)->bought)) {
                $voucher = Voucher::findOne($model->epa_vou_id)->bought;
                $voucher->scenario = 'ready_to_sell';
                $voucher->vob_ready_to_sell = (int)$_POST['vob_ready_to_sell'];
                if($voucher->save(false)) {
                    if($voucher->vob_status == 1) {
                        $epay = Epay::find()->where('epa_vou_id = :id', [':id' => $model->epa_vou_id])->one();
                        $success = 0;
                        if(!empty($epay)) {
                            $success = $epay->epa_success_qty;
                        }
                        $voucher = Voucher::find()->where('vou_id = :id', [':id' => $model->epa_vou_id])->one();
                        $voucher->vou_stock_left = $voucher->vou_stock_left + $success;
                        $voucher->save(false);
                    }

                    $audit = AuditReport::setAuditReport('ready to sell: '.$voucher->vou_reward_name, Yii::$app->user->id, Voucher::className(), $voucher->vou_id)->save();
                    $this->setMessage('save', 'success', 'Voucher has been successfully ready for sell!');
                    $result = [
                        'url' => $this->getRememberUrl()
                    ];
                    return \yii\helpers\Json::encode($result);
                }
            }
        }
    }

    public function actionView($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => EpayDetail::find()->where(['epd_epa_id' => $id])->voucher(),
            'pagination' => [
                'pageSize' => $this->_pageSize,
            ],
        ]);

        return $this->render('view', [
            'id' => $id,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCancel()
    {
        return $this->redirect([$this->getRememberUrl()]);
    }

    public function actionCreate()
    {
        $model = new Epay();
        if ($model->load(Yii::$app->request->post())) {
            $voucher = Voucher::findOne($model->epa_vou_id);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // fetch product info
                $model->epa_epp_id = $voucher->vou_epp_id;
                $product = $model->productInfo();

                // save voucher bought
                $voucherBought = new VoucherBought();
                $voucherBought->vob_com_id = null;
                $voucherBought->vob_qty = $model->epa_qty;
                $voucherBought->vob_price = $product->epp_amount_incent;
                $voucherBought->vob_vou_id = $model->epa_vou_id;
//                echo '<pre>';
//                var_dump($voucherBought);exit;
                if ($voucherBought->save()) {
                    $model->epa_datetime = !empty($model->epa_datetime) ? strtotime($model->epa_datetime) : time();
                    $model->epa_admin_id = Yii::$app->user->id;
                    $model->epa_admin_name = Yii::$app->user->identity->username;
                    $model->epa_success_qty = 0;
                    $model->epa_failed_qty = 0;
                    $model->epa_vob_id = $voucherBought->vob_id;

                    $now = date('Y-m-d');
                    $yesterday = date('Y-m-d', strtotime('-1 days'));

                    if ($model->save()) {
                        $transaction->commit();
			
                        $console = new ConsoleRunner(['file' => '@app/yii']);
                        $console->run('epay/buy '.$model->epa_id);

                        $this->setMessage('save', 'success');
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollback();
                        $errorMessages = '';
                        $errorBase = $voucherBought->getErrors();
                        foreach ($voucherBought->getErrors() as $k => $error) {
                            $errorMessages .= $errorBase[$k][0] . '<br/>';
                        }
                        $this->setMessage('save', 'error', $errorMessages);
                    }
                } else {
                    $transaction->rollback();
                    $this->setMessage('save', 'error');
                }
            } catch (Exception $e) {
                $transaction->rollback();
                $this->setMessage('save', 'error', $e->getMessage());
            }
            // return $this->redirect([$this->getRememberUrl()]);
            return $this->refresh();
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }
    
    public function actionBebas()
    {
        $postParams = [
            'service' => $this->EPAYSVC_NETWORKCHECK,
            'amount' => 0,
            'product' => '',
            'msisdn' => '0',
        ];

        $curl_request = $this->processEpay($postParams);
        var_dump($curl_request);
        exit;
    }
}