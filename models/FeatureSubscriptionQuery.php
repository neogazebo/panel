<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class FeatureSubscriptionQuery extends ActiveQuery {

    public function getList()
    {
        if(isset($_GET['search'])) {
            $this->andWhere(['LIKE', 'fes_code', $_GET['search']]);
            $this->orWhere(['LIKE', 'fes_name', $_GET['search']]);
            $this->orWhere(['LIKE', 'fes_price', $_GET['search']]);
            $this->orWhere(['LIKE', 'fes_currency', $_GET['search']]);
            $this->orWhere(['LIKE', 'fes_description', $_GET['search']]);
        }
        return $this;
    }
    
    public function getFesCodeFesName()
    {
        $this->select('fes_code,fes_name');
        $this->asArray();
        $this->andWhere('fes_tm = 1 AND fes_hide = 0 AND fes_trial = :trial', [
            'trial' => intval(Yii::$app->request->post('trial'))
        ]);
        
        return $this;
    }

}
