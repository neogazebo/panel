<?php

namespace app\commands;

use yii\console\Controller;
use app\models\EpayPrelogTrx;

class EpaybridgeController extends Controller
{
    protected $EPAY_API_URL = 'https://admin.ebizu.com/app/epay';
    protected $EPAY_TOKEN_API = '3f2d64f31ab572ecf322d06a2b961755';
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
    protected $EPAY_URL = 'ws1.oriongateway.com:22831';
    // protected $EPAY_URL = 'wstest.oriongateway.com:22837';
    protected $EPAY_URL_PATH = '/willani/services/oglws';
    // protected $EPAY_URL = 'ws.oriongateway.com:33831';
    // protected $EPAY_URL_PATH = '/averni/services/oglws';

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

        $file_in_command = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'epay' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR;
        $command = '/usr/bin/java -jar ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'epay' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'EpayTLS.jar ' . $this->EPAY_URL . ' ' . $this->EPAY_URL_PATH . ' ' . $service . ' ' . $file_in_command . ' ' . implode(' ', $array);
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