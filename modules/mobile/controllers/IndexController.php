<?php

namespace app\modules\mobile\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use app\models\MobilePulsaProduct;
use app\models\MobilePulsaTopup;

/**
 * Default controller for the `mobile` module
 *
 * @author Ilham Fauzi <ilham@ebizu.com>
 */
class IndexController extends MpController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $dataProviderProduct = new ActiveDataProvider([
            'query' => MobilePulsaProduct::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        
        return $this->render('index', [
            'dataProviderProduct' => $dataProviderProduct,
            'balance' => $this->getBalance(),
            'totalTopup' => \app\models\MobilePulsaTopup::find()->count(),
            'connected' => $this->getConnection(),
        ]);
    }

    protected function getBalance()
    {
        $actionURL = $this->_url . $this->_action_checkbalance;

        $post = json_encode(array(
            't' => $this->_token,
            'd' => array(
                'sanbox' => false,
            ),
        ));
        $param = 'r=' . $post;
        $response = $this->makeRequest($actionURL, $param);

        if ($response === null) {
            return 0;
        } else {
            if (!empty($response->d->data->balance))
                return number_format(round($response->d->data->balance, 2), 2);
            else
                return 0;
        }
    }
    
    protected function getConnection()
    {
        $actionURL = $this->_url . $this->_action_checkbalance;

        $post = json_encode(array(
            't' => $this->_token,
            'd' => array(
                'sanbox' => false,
            ),
        ));
        $param = 'r=' . $post;
        $response = $this->makeRequest($actionURL, $param);

        if ($response !== null)
            return true;
        return false;
    } 
}
