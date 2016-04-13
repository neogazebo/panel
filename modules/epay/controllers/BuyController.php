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
        $model = Epay::find()->with(['reward'])->voucher(true);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => $this->_pageSize,
            ],
        ]);
        $model->joinWith(['reward' => function($model) {
            $model->from(['reward' => 'tbl_voucher']);
        }]);

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
            'query' => EpayDetail::find()->voucher(true),
            'pagination' => [
                'pageSize' => $this->_pageSize,
            ],
        ]);

        return $this->render('view', [
            'id' => $id,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Epay();
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $productID = Voucher::findOne($model->epa_vou_id)->vou_epp_id;
                $model->epa_epp_id = $productID;
                $model->epa_admin_id = Yii::$app->user->identity->id;
                $model->epa_admin_name = Yii::$app->user->identity->username;
                $model->epa_success_qty = 0;
                $model->epa_failed_qty = 0;

                $now = date('Y-m-d');
                $yesterday = date('Y-m-d', strtotime('-1 days'));

                if ($model->save()) {
                    // fecth product info
                    $product = $model->productInfo();

                    // save voucher bought
                    $voucherBought = new VoucherBought();
                    $voucherBought->vob_com_id = null;
                    $voucherBought->vob_qty = $model->epa_qty;
                    $voucherBought->vob_price = $product->epp_amount_incent;
                    $voucherBought->vob_vou_id = $model->epa_vou_id;

                    if ($voucherBought->save()) {
                        $transaction->commit();

                        $cr = new ConsoleRunner(['file' => '@app/yii']);
                        $cr->run('epay/buy ' . $model->epa_id);

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
                        return $this->redirect(['index']);
                    }
                } else {
                    $transaction->rollback();
                    $this->setMessage('save', 'error');
                    return $this->redirect(['index']);
                }
            } catch (Exception $e) {
                $transaction->rollback();
                $this->setMessage('save', 'error', $e->getMessage());
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('form', [
                'model' => $model,
            ]);
        }
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
