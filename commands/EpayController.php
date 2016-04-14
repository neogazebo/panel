<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Application;
use fedemotta\cronjob\models\CronJob;
use app\commands\EpaybridgeController;
use app\models\User;
use app\models\Epay;
use app\models\EpayDetail;
use app\models\VoucherBought;
use app\models\VoucherBoughtDetail;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class EpayController extends EpaybridgeController {

    /**
     * Run SomeModel::some_method for a period of time
     * @param string $from
     * @param string $to
     * @return int exit code
     */
    // public function actionInit($from, $to)
    // {
    //     $dates  = CronJob::getDateRange($from, $to);
    //     $command = CronJob::run($this->id, $this->action->id, 0, CronJob::countDateRange($dates));
    //     if ($command === false) {
    //         return Controller::EXIT_CODE_ERROR;
    //     } else {
    //         foreach ($dates as $date) {
    //             //this is the function to execute for each day
    //             // SomeModel::some_method((string) $date);
    //             User::find()->getCron((string) $date);
    //         }
    //         $command->finish();
    //         return Controller::EXIT_CODE_NORMAL;
    //     }
    // }

    public function actionBuy($id) {
//	echo $id;exit;
        $success = 0;
        $fail = 0;

        $model = Epay::findOne($id);
        $voucherBought = VoucherBought::find()
                ->where('vob_vou_id = :vou_id', [':vou_id' => $model->epa_vou_id])
                ->one();
        $product = $model->productInfo();

        for ($i = 1; $i <= $model->epa_qty; $i++) {
            try {
                $postParams = json_encode([
                    't' => Yii::$app->params['EPAY_TOKEN_API'],
                    'd' => [
                        'service' => Yii::$app->params['EPAYSVC_ONLINEPIN'],
                        'amount' => $product->epp_amount_incent,
                        'product' => $product->epp_product_code,
                        'msisdn' => '0',
                        'thirdapp' => 1,
                    ],
                ]);
                // using API script
                // not used
                // $curl_request = $this->requestAPI($postParams);
                // using local script
                $local_request = $this->processEpay($postParams);
                if ($local_request !== false) {
                    $result = $local_request;
                    if (isset($result['response']) && !empty($result['response'])) {
                        echo $i.'. :'.$result['response']->responseCode.'<br>';
                        $detail = new EpayDetail();
                        $detail->epd_epa_id = $model->epa_id;
                        $detail->epd_red_id = 3;
                        $detail->epd_request = 'PIN';
                        $detail->epd_amount = $result['response']->amount;
                        $detail->epd_merchant_id = Yii::$app->params['MERCHANT_ID'];
                        $detail->epd_operator_id = Yii::$app->params['OPERATOR_ID'];
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

        $voucherBought->vob_status = 1;
        $voucherBought->save(false);

        $model->epa_success_qty = $success;
        $model->epa_failed_qty = $fail;
        $model->epa_vob_id = $voucherBought->vob_id;
        $model->save(false);

        return 1;
    }

    /**
     * Run SomeModel::some_method for today only as the default action
     * @return int exit code
     */
    public function actionIndex() {
        echo var_dump(Yii::$app->params);
        exit;
        // return $this->actionInit(date("Y-m-d"), date("Y-m-d"));
    }

    /**
     * Run SomeModel::some_method for yesterday
     * @return int exit code
     */
    public function actionYesterday() {
        return $this->actionInit(date("Y-m-d", strtotime("-1 days")), date("Y-m-d", strtotime("-1 days")));
    }

}
