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
            $speciality_id = intval(Yii::$app->request->post('com_speciality'));
            $children = json_decode(Yii::$app->request->post('children'));
            $op_confirmation = Yii::$app->request->post('op_confirmation');

            $merchant_id = Company::find()->getGroupMerchantSpeciality($speciality_id);

            $changes = array_merge(
                array_diff($children, $merchant_id),
                array_diff($merchant_id, $children)
            );
            $change_name = Company::find()->getChildrenNames($changes);

            if (!$changes) {
                throw new \Exception('No changes were made, so no operation will be performed.', 1000);
            }
            // if user have not confirm the operation yet
            if(!$op_confirmation) {
                $message = Yii::$app->controller->renderPartial('partials/child_op', ['data' => $change_name, 'op' => 'add']);
                return $this->json_helper->jsonOutput(self::CHILDREN_OP_CONFIRMATION_CODE, 'error', $message);
            }
            $company_special = CompanySpeciality::find()->with('type')->where('com_spt_id = :id',[
                    ':id' => $speciality_id
                ])->one();
            $new_speciality_id = $company_special->type->com_type_id;
            Company::find()->changeSpecialityMerchant($new_speciality_id,$changes);
            
            $transaction->commit();
            return $this->json_helper->jsonOutput(0, 'success', null);

        } catch(\Exception $e) {
            $transaction->rollback();
            return $this->json_helper->jsonOutput(1000, 'error', $e->getMessage());
        }

    }
}
