<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\EpayPrelogTrx;

class EpaybridgeController extends Controller
{
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

	protected function processEpay($d)
    {
        $params_bebas = json_decode($d);
        $model = new EpayPrelogTrx();
        $params = array(
            'productCode' => $params_bebas->d->product,
            'msisdn' => $params_bebas->d->msisdn,
            'amount' => $params_bebas->d->amount,
            'merchantId' => Yii::$app->params['MERCHANT_ID'],
            'operatorId' => Yii::$app->params['OPERATOR_ID'],
            'retTransRef' => 'MA-' . time() . substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"), 0, 20),
            'terminalId' => Yii::$app->params['TERMINAL_ID'],
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
            case Yii::$app->params['EPAYSVC_NETWORKCHECK'] :
                $data['response'] = $this->epayJar('networkCheck', $params);
                $request_type = 'NET-CHECK';
                break;
            case Yii::$app->params['EPAYSVC_ONLINEPIN'] :
                $data['response'] = $this->epayJar('onlinePIN', $params);
                $request_type = 'PIN';
                break;
            case Yii::$app->params['EPAYSVC_ONLINEPIN_REVERSAL'] :
                $data['response'] = $this->epayJar('onlinePINReversal', $params);
                $request_type = 'PIN-REV';
                break;
            case Yii::$app->params['EPAYSVC_ETOPUP'] :
                $data['response'] = $this->epayJar('etopup', $params);
                $request_type = 'ETU';
                break;
            case Yii::$app->params['EPAYSVC_ETOPUP_REVERSAL'] :
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
        $command = '/usr/bin/java -jar ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'epay' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'EpayTLS.jar ' . Yii::$app->params['EPAY_URL'] . ' ' . Yii::$app->params['EPAY_URL_PATH'] . ' ' . $service . ' ' . $file_in_command . ' ' . implode(' ', $array);
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
