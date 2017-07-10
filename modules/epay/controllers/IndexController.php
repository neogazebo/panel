<?php

namespace app\modules\epay\controllers;


use Yii;
/*
 */

/**
 * Description of IndexController
 *
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 */
class IndexController extends EpaybaseController
{
    public function actionIndex(){
        return $this->render('index');
        // return $this->redirect(['/auth/login']);
    }

    /*
     * Check connection
     */
    public function actionCheckConnection()
    {
        $tStart = $this->msec();
        try {
            $postParams = json_encode([
                't' => $this->EPAY_TOKEN_API,
                'd' => [
                    'service' => $this->EPAYSVC_NETWORKCHECK,
                    'amount' => 0,
                    'product' => '',
                    'msisdn' => '0',
                ],
            ]);
            $result = (object) $this->processEpay($postParams);
            $result = (object) $result;

            echo json_encode([
                'status' => isset($result->response->responseCode) ? $result->response->responseCode : 500,
                'message' => isset($result->response->responseMsg) ? $result->response->responseMsg : 'Internal server error',
                'execution_time' => round(($this->msec() - $tStart), 2),
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => "212", // error
                'message' => $e->getMessage(),
                'execution_time' => round(($this->msec() - $tStart), 2),
            ]);
        }
    }

}
