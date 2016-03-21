<?php

namespace app\modules\epay\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Epay;
use app\models\EpayDetail;
use app\models\Voucher;
use app\models\VoucherBought;
use app\models\VoucherBoughtDetail;

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
        $dataProvider = new ActiveDataProvider([
            'query' => Epay::find()->voucher(true),
            'pagination' => [
                'pageSize' => $this->_pageSize,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
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
                $productID = $_POST['Epay']['epa_epp_id'];
                $model->epa_epp_id = $productID;
                $model->epa_admin_id = Yii::$app->user->identity->id;
                $model->epa_admin_name = Yii::$app->user->identity->username;
                $model->epa_success_qty = 0;
                $model->epa_failed_qty = 0;

                if ($model->save()) {
                    $success = 0;
                    $fail = 0;

                    // fecth product info
                    $product = $model->productInfo();

                    // save voucher bought
                    $voucherBought = new VoucherBought();
                    $voucherBought->vob_com_id = null;
                    $voucherBought->vob_qty = $model->epa_qty;
                    $voucherBought->vob_price = $product->epp_amount_incent;
                    $voucherBought->vob_vou_id = $model->epa_vou_id;

                    if ($voucherBought->save()) {
                        for ($i = 1; $i <= $model->epa_qty; $i++) {
                            try {
                                $postParams = json_encode(array(
                                    't' => $this->EPAY_TOKEN_API,
                                    'd' => array(
                                        'service' => $this->EPAYSVC_ONLINEPIN,
                                        'amount' => $product->epp_amount_incent,
                                        'product' => $product->epp_product_code,
                                        'msisdn' => '0',
                                        'thirdapp' => 1,
                                    ),
                                ));
                                // using API script
                                // not used
//                                $curl_request = $this->requestAPI($postParams);
                                // using local script
                                $local_request = $this->processEpay($postParams);
                                if ($local_request !== false) {
                                    $result = $local_request;

                                if (isset($result['response']) && !empty($result['response'])) {
                                    $detail = new EpayDetail();
                                    $detail->epd_epa_id = $model->epa_id;
                                    $detail->epd_red_id = 3;
                                    $detail->epd_request = 'PIN';
                                    $detail->epd_amount = $result['response']->amount;
                                    $detail->epd_merchant_id = $this->MERCHANT_ID;
                                    $detail->epd_operator_id = $this->OPERATOR_ID;
                                    $detail->epd_org_trans_ref = (isset($result['response']->orgTransRef) && !empty($result['response']->orgTransRef)) ? $result['response']->orgTransRef : null;
                                    $detail->epd_ret_trans_ref = $result['response']->retTransRef;
                                    $detail->epd_terminal_id = $result['response']->terminalId;
                                    $detail->epd_product_code = $result['response']->productCode;
                                    $detail->epd_msisdn = !empty($params['msisdn']) ? $params['msisdn'] : null;
                                    $detail->epd_trans_datetime = $result['transDateTime'];
                                    $detail->epd_trans_trace_id = $result['transTraceId'];
                                    $detail->epd_custom_field_1 = isset($result['response']->customField1) ? $result['response']->customField1 : null;
                                    $detail->epd_custom_field_2 = isset($result['response']->customField2) ? $result['response']->customField2 : null;
                                    $detail->epd_custom_field_3 = isset($result['response']->customField3) ? $result['response']->customField3 : null;
                                    $detail->epd_custom_field_4 = isset($result['response']->customField4) ? $result['response']->customField4 : null;
                                    $detail->epd_custom_field_5 = isset($result['response']->customField5) ? $result['response']->customField5 : null;
                                    $detail->epd_macing = null;
                                    $detail->epd_pin = isset($result['response']->pin) ? $result['response']->pin : null;
                                    $detail->epd_pin_expiry_date = $result['response']->pinExpiryDate;
                                    $detail->epd_response_code = $result['response']->responseCode;
                                    $detail->epd_trans_ref = $result['response']->transRef;

                                    if ($detail->save(false)) {
                                        // create voucher
                                        if ($result['response']->responseCode == '00') {
                                            // save voucher bought detail
                                            $voucherBoughtDetail = new VoucherBoughtDetail();
                                            $voucherBoughtDetail->vod_vob_id = $voucherBought->vob_id;
                                            $voucherBoughtDetail->vod_sn = ltrim($result['response']->transRef, '.');
                                            $voucherBoughtDetail->vod_code = $result['response']->pin;

                                            $fulldate = !empty($result['response']->pinExpiryDate) ? substr($result['response']->pinExpiryDate, 4, 2) . '/' . substr($result['response']->pinExpiryDate, 2, 2) . '/' . substr($result['response']->pinExpiryDate, 0, 2) : null;
                                            $pinExpiryDate = ($fulldate !== null) ? str_replace('/', '-', $this->convertTwoYearToFour($fulldate)) : 0;
                                            $voucherBoughtDetail->vod_expired = intval(strtotime($pinExpiryDate));
                                            $voucherBoughtDetail->vou_redeemed = 0;
                                            $voucherBoughtDetail->save(false);
                                            $success++;
                                        } else {
                                            $fail++;
                                        }
                                    } else {
                                        $fail++;
                                        break;
                                    }
                                } else {
                                    break;
                                }
                                } else {
                                    $fail++;
                                }
                            } catch (Exception $e) {
                                $fail++;
                            }
                            sleep(3); // delay 3 second for each loop
                        }
                        
                        $model->epa_success_qty = $success;
                        $model->epa_failed_qty = $fail;
                        $model->save(false);
                        
                        // update stock left voucher
                        $voucher = Voucher::find()->where('vou_id=:id',['id'=>$model->epa_vou_id])->one();
                        $voucher->vou_stock_left = $voucher->vou_stock_left + $success;
                        $voucher->save(false);
                        
                        $transaction->commit();
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
