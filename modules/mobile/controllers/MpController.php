<?php

namespace app\modules\mobile\controllers;

use Yii;
use app\controllers\BaseController;

/**
 * Description of MpController
 *
 * @author Tajhul Faijin <mrazoelcalm@gmail.com>
 */
class MpController extends BaseController {
    
    protected $_urldev = 'https://apix.ebizu.com/mobilepulsa/';
    protected $_url = 'https://api.ebizu.com/mobilepulsa/';
    protected $_action_pricelist = 'pricelist';
    protected $_action_checkbalance = 'checkbalance';
    protected $_action_checkstatus = 'checkstatus';
    protected $_action_topup = 'topup';
    
    protected $_token = '3f2d64f31ab572ecf322d06a2b961755';
    
    protected function makeRequest($url, $param){
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
         // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);        
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $data = curl_exec($ch);
         curl_close($ch);       
         
        return json_decode($data);
    }
    
}
