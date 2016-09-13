<?php

namespace app\modules\system\processors\merchant_hq;

use Yii;
use app\modules\bases\BaseProcessor;
use app\models\Company;

class MerchantHqDeleteProcessor extends BaseProcessor
{
    public function process()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            $id = Yii::$app->request->post('com_id');
            $model = Company::find()->where(['com_id' => $id])->one();
            $merchant_children = Company::find()->getChildMerchants($id)->asArray()->all();
            $scenario = 'delete-hq';

            $title = 'Company - Delete Merchant HQ';

            $activities = [
                $title,
                $title . ' ' . $model->com_email . ' on ' . $model->com_name,
                Company::className(),
                $model->com_id
            ];
            
            if($merchant_children) {
                throw new \Exception("You could not delete HQ that have children. Please empty the children first", self::SYSTEM_ERROR_CODE);
            }

            $model->setScenario($scenario);

            if($model->delete()) {
                $this->saveLog($activities);
                $transaction->commit();
                return $this->json_helper->jsonOutput(0, 'success', null);
            }

            return $this->json_helper->jsonOutput(self::VALIDATION_ERROR_CODE, 'error', $model->getErrors());
        } catch(\Exception $e) {
            $transaction->rollback();
            return $this->json_helper->jsonOutput(self::SYSTEM_ERROR_CODE, 'error', $e->getMessage());
        }
    }
}
