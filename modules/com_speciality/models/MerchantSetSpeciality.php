<?php

namespace app\modules\com_speciality\models;

use Yii;
use app\models\Company;
use app\models\CompanySpeciality;
use app\models\CompanyType;
use app\modules\bases\BaseProcessor;

class MerchantSetSpeciality extends BaseProcessor
{
    public function process()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $speciality_id = Yii::$app->request->post('spt_id');
            $children = json_decode(Yii::$app->request->post('children'));

            $op_confirmation = Yii::$app->request->post('op_confirmation');

            $merchant_id = Company::find()->getGroupMerchantSpeciality(intval($speciality_id));
            $changes = array_merge(
                array_diff($children, $merchant_id),
                array_diff($merchant_id, $children)
            );
            if ($changes) {
                
            }
        } catch(\Exception $e) {
            $transaction->rollback();
            return $this->json_helper->jsonOutput(1000, 'error', $e->getMessage());
        }

    }
}
