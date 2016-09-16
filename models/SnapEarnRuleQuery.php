<?php
namespace app\models;

use Yii;
use app\components\helpers\SnapearnPointSpeciality;
use app\models\SnapEarn;
use yii\db\ActiveQuery;

class SnapEarnRuleQuery extends ActiveQuery
{
    public function all($db = null)
    {
        return parent::all($db);
    }

    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getPointSnapEarnRule()
    {
       	$id = Yii::$app->request->post('id');
        $amount = Yii::$app->request->post('amount');
        $com_id = Yii::$app->request->post('com_id');
        $trans_time = Yii::$app->request->post('transaction_time');
        $business = Company::findOne($com_id);
        $se = SnapEarn::findOne($id);

        $config = SnapEarnRule::find()->where(['ser_country' => $se->member->country->cty_currency_name_iso3])->one();
        $point = $amount;

        if ($config->ser_point_provision > 0 ) {
            $point = (int) ($amount / $config->ser_point_provision);
        }

        if (!empty($business)) {
            if(!empty($config)) {
                $speciality = new SnapearnPointSpeciality;
                $point_config = $speciality->getActivePoint($id, strtotime($trans_time));
                $day = $point_config['day_promo'];
                $trans_day = date('l',strtotime($trans_time));
                if($day == $trans_day){
                    $point *= $point_config['point'];
                    $point_cap = $point_config['max_point'];
                }else{
                    $point *= $point_config['point'];
                    $point_cap = $point_config['max_point'];
                }

                if($point > $point_cap)
                    return $point_cap;
            }
            return $point;
        } else {
            return "empty-b";
        }
    }
}