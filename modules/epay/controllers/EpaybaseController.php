<?php

namespace app\modules\epay\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\BaseController;
use SoapClient;
use app\models\EpayPrelogTrx;
use app\models\Epay;
use app\models\EpayDetail;

/**
 * Description of EpaybaseController
 *
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 * @author Ilham Fauzi <ilham@ebizu.com>
 */
class EpaybaseController extends BaseController
{
    // protected $EPAY_API_URL = 'https://api.ebizu.com/app/epay';
    protected $EPAY_API_URL = 'https://admin.ebizu.com/app/epay';
    protected $EPAY_TOKEN_API = '3f2d64f31ab572ecf322d06a2b961755'; //harcode merujuk ke=> red_key in tbl_redemption_partner => EBC data
    protected $EPAYSVC_NETWORKCHECK = 'networkCheck';
    protected $EPAYSVC_ONLINEPIN = 'onlinePIN';
    protected $EPAYSVC_ONLINEPIN_REVERSAL = 'onlinePINReversal';
    protected $EPAYSVC_ETOPUP = 'etopup';
    protected $EPAYSVC_ETOPUP_REVERSAL = 'etopupReversal';
    protected $OPERATOR_ID = 'IBS';
    protected $MERCHANT_ID = '202433';
    protected $TERMINAL_ID = '80017419';
    protected $EPAY_OPERATOR_ID = 'IBS';
    protected $EPAY_TERMINAL_ID = '80017419';

    //epay url
    protected $EPAY_URL = 'ws1.oriongateway.com:22831';
    // protected $EPAY_URL = 'wstest.oriongateway.com:22837';
    // protected $EPAY_URL = 'ws.oriongateway.com:33831';
    protected $EPAY_URL_PATH = '/willani/services/oglws';
    // protected $EPAY_URL_PATH = '/averni/services/oglws';

    public function init()
    {
        parent::init();
        date_default_timezone_set('Etc/UTC');
        $this->enableCsrfValidation = false;
    }

    protected function convertTwoYearToFour($str)
    {
        // Our date
        //$str = "01/04/10";
        $cutoff = 50;
        // See what YY is
        // Get the substring of $str starting two from the end (-2)... this is YY
        $year = substr($str, -2);
        // Check whether year added should be 19 or 20
        if ($year < 50)
        // PHP converts string to number nicely, so this is our 20YY
            $year += 2000;
        else
        // This is 19YY
            $year += 1900;
        // Repace YY with YYYY
        // This will take $str and replace the part two from the end (-2) undtil
        // the end with $year.
        $str = substr_replace($str, $year, -2);
        // See what we got
        return $str;
    }

    /*
     * Get Milisecond time
     */
    protected function msec()
    {
        list($usec, $sec) = explode(" ", microtime());
        $mSec = ((float) $usec / 1000) + (float) $sec;
        return $mSec;
    }

    // wrap curl request
//    protected function requestAPI($data) {
//        $post = 'r=' . $data;
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $this->EPAY_API_URL);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // make it json
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        return curl_exec($ch);
        //curl_close($ch);
//    }

    // @todo : pindahin ke library
    protected function epayOnlinePINRequest($params)
    {
        try {
            $client = new SoapClient($this->EPAY_URL, array(
                'trace' => 1,
            ));
            $result = $client->onlinePin(array('in0' => $params));
            ////return $result->onlinePINReturn->responseCode;
            return $result->onlinePINReturn;
        } catch (SoapFault $exception) {
            ////$exception->getMessage();
            return false;
        }
    }

    // public function setMessage($key, $type, $customText = null)
    // {
    //     switch ($key) {
    //         case 'save' :
    //             Yii::$app->session->setFlash($type, $customText !== null ? Yii::t('app', $customText) : Yii::$app->params['flashmsg']['save'][$type]);
    //             break;
    //         case 'update' :
    //             Yii::$app->session->setFlash($type, $customText !== null ? Yii::t('app', $customText) : Yii::$app->params['flashmsg']['update'][$type]);
    //             break;
    //         case 'delete' :
    //             Yii::$app->session->setFlash($type, $customText !== null ? Yii::t('app', $customText) : Yii::$app->params['flashmsg']['delete'][$type]);
    //             break;
    //     }
    // }

    // method processing epay
    protected function processEpay($d)
    {
        $params_bebas = json_decode($d);
        $model = new EpayPrelogTrx();
        $params = array(
            'productCode' => $params_bebas->d->product,
            'msisdn' => $params_bebas->d->msisdn,
            'amount' => $params_bebas->d->amount,
            'merchantId' => $this->MERCHANT_ID,
            'operatorId' => $this->OPERATOR_ID,
            'retTransRef' => 'MA-' . time() . substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"), 0, 20),
            'terminalId' => $this->TERMINAL_ID,
            'transDateTime' => date('YmdHis'),
            'transTraceId' => substr(str_shuffle("01234567891011121314151617181920"), 0, 6), // 6 character number 1-999999
        );

        // validation if params not array
        if (!is_array($params)) {
            return array('error' => null, 'response' => null);
        }

        $data = array('error' => null);

        $request_type = 'NET-CHECK';
        switch ($params_bebas->d->service) {
            case $this->EPAYSVC_NETWORKCHECK :
                $data['response'] = $this->epayJar('networkCheck', $params);
                $request_type = 'NET-CHECK';
                break;
            case $this->EPAYSVC_ONLINEPIN :
                $data['response'] = $this->epayJar('onlinePIN', $params);
                $request_type = 'PIN';
                break;
            case $this->EPAYSVC_ONLINEPIN_REVERSAL :
                $data['response'] = $this->epayJar('onlinePINReversal', $params);
                $request_type = 'PIN-REV';
                break;
            case $this->EPAYSVC_ETOPUP :
                $data['response'] = $this->epayJar('etopup', $params);
                $request_type = 'ETU';
                break;
            case $this->EPAYSVC_ETOPUP_REVERSAL :
                $data['response'] = $this->epayJar('etopupReversal', $params);
                $request_type = 'ETU-REV';
                break;
            default :
                $data['response'] = $this->epayJar('networkCheck', $params);
                $request_type = 'NET-CHECK';
                break;
        }
        $data['transDateTime'] = $params['transDateTime'];
        $data['transTraceId'] = $params['transTraceId'];

        // save pre log trx
        $model->ept_datetime = time();
        $model->ept_red_id = NULL;
        $model->ept_product_type = $request_type;
        $model->ept_ret_trans_ref = $params['retTransRef'];
        $model->ept_transTraceId = $params['transTraceId'];
        $model->ept_product_code = $params['productCode'];
        $model->ept_amount = $params['amount'];
        $model->ept_msisdn = $params['msisdn'];
        $model->ept_related_product_id = 0;
        if(!$model->save()) {
            $this->setMessage('save', 'error', General::extactErrorModel($model->getErrors()));
        }
        return $data;
    }

    protected function epayJar($service, $params)
    {
        $array = [];
        foreach ($params as $key => $value) {
            $array[] = '"' . $key . '=' . $value . '"';
        }

        $command = '/usr/bin/java -jar ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'EpayTLS.jar ' . $this->EPAY_URL . ' ' . $this->EPAY_URL_PATH . ' ' . $service . ' ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . ' ' . implode(' ', $array);
        $xml = `$command`;
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($xml), $array);
        xml_parser_free($parser);

        $response = array();
        foreach ($array as $data) {
            if ($data['level'] == 5 && $data['type'] == 'complete') {
                $response[$data['tag']] = isset($data['value']) ? $data['value'] : '';
            }
        }
        return (object) $response;
    }
}
