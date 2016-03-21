<?php

namespace app\modules\epay\controllers;

/**
 * Description of IndexController
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 */
class IndexController extends EpaybaseController
{
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
            $post = json_encode([
                't' => $this->EPAY_TOKEN_API,
                'd' => array(
                    'service' => $this->EPAYSVC_NETWORKCHECK,
                    'amount' => 0,
                    'product' => 'CELCOMAIRTIME',
                    'msisdn' => '0136300813',
                    'thirdapp' => 1, //
                ),
            ]);
            $post2 = 'r=' . $post;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->EPAY_API_URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post2); // make it json
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            $result = json_decode($data);
            curl_close($ch);
            echo json_encode([
                'status' => $result->response->responseCode,
                'message' => $result->response->responseMsg,
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
