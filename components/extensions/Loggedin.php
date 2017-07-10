<?php
 
namespace app\components\extensions;

use Yii;
use yii\base\Component;
use app\models\Currency;

class Loggedin extends Component {

    protected $company = false;
    protected $principal = false;
    protected $user = false;
    protected $currency;

    public function init()
    {
        if (!Yii::$app->user->isGuest){
            $this->user = Yii::$app->user->identity;
            if(isset($this->user->usr_type_id) && $this->user->usr_type_id == 4){
                $this->principal = Yii::$app->user->identity->principal;
                $this->currency = !empty($this->principal->prc_currency) ? Currency::find()->where('cur_id=:cur', ['cur' => $this->principal->prc_currency])->one() : null;
            } else if(isset($this->user->type) && $this->user->type == 1) {
                
            }else{
                $this->company = Yii::$app->user->identity->company;
                $this->currency = !empty($this->company->com_currency) ? Currency::find()->where('cur_id=:cur', ['cur' => $this->company->com_currency])->one() : null;
            }
            
        }
        return parent::init();
    }
    
    public function getCompany(){
        return $this->company;
    }

    public function getPrincipal(){
        return $this->principal;
    }
    
    public function getUser(){
        return $this->user;
    }
    
    public function getCurrency(){
        return $this->currency;
    }

}
