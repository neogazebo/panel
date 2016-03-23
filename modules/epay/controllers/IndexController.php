<?php

namespace app\modules\epay\controllers;

/*
 */

/**
 * Description of IndexController
 *
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 */
class IndexController extends EpaybaseController {

    public function actionIndex()
    {
        return $this->render('index', []);
    }

    /*
     * Check connection
     */

    public function actionCheckConnection()
    {
        $tStart = $this->msec();
        try {
            $postParams = json_encode(array(
                't' => $this->EPAY_TOKEN_API,
                'd' => array(
                    'service' => $this->EPAYSVC_NETWORKCHECK,
                    'amount' => 0,
                    'product' => '',
                    'msisdn' => '0',
                ),
            ));
            $result = (object) $this->processEpay($postParams);
            $result = (object) $result;


            echo json_encode(array(
                'status' => isset($result->response->responseCode) ? $result->response->responseCode : 500,
                'message' => isset($result->response->responseMsg) ? $result->response->responseMsg : 'Internal server error',
                'execution_time' => round(($this->msec() - $tStart), 2),
            ));
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => "212", // error
                'message' => $e->getMessage(),
                'execution_time' => round(($this->msec() - $tStart), 2),
            ));
        }
    }

}
