<?php

namespace app\modules\com_speciality\models;

use Yii;
use app\models\Company;
use app\models\CompanySpeciality;
use app\modules\bases\BaseProcessor;

class MerchantSetSpeciality extends BaseProcessor
{
    public function process()
    {
        $transaction = Yii::$app->db->beginTransaction();

        // try {
            $speciality_id = Yii::$app->request->post('com_speciality');
            $children = Yii::$app->request->post('children');
            var_dump(count($children));exit;
            $country = CompanySpeciality::find()->with('country')->where('com_spt_id = :id',[
                    ':id' => 1
                ])->one();
            $cty_id = $country->country->cty_currency_name_iso3;
            $op_confirmation = Yii::$app->request->post('op_confirmation');

            $merchant_id = Company::find()->getGroupMerchantSpeciality($speciality_id,$cty_id);
            var_dump($children);exit;
            // Company::find()->saveMerchantSpeciality($speciality_id, $merchant_id);
            // $changes = array_merge(
            //     array_diff($children, $merchant_id),
            //     array_diff($merchant_id, $children)
            // );
            // var_dump($changes);exit;
        // } catch(\Exception $e) {
        //     $transaction->rollback();
        //     var_dump($e);exit;
        //     return $this->json_helper->jsonOutput(1000, 'error', $e->getMessage());
        // }

    }
}
