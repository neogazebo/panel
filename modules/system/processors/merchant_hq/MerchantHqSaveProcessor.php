<?php

namespace app\modules\system\processors\merchant_hq;

use Yii;
use app\modules\system\processors\bases\BaseProcessor;
use app\models\Company;

class MerchantHqSaveProcessor extends BaseProcessor
{
    public function process()
    {
        $op = Yii::$app->request->post('op');
        $id = Yii::$app->request->post('com_id');

        switch ($op) {
            case 'add':
                $model = new Company();
                $scenario = 'new-hq';
                $title = 'Company - Add New Merchant HQ';
                break;
            case 'edit':
                $model = Company::findOne($id);
                $scenario = 'update-hq';
                $title = 'Company - Update Merchant HQ';
                break;
        }
        $activities = [
            $title,
            $title . ' ' . $model->com_email . ' on ' . $model->com_name,
            Company::className(),
            $model->com_id
        ];

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->setScenario($scenario);

            if($model->load(Yii::$app->request->post(), '')) {
                if($model->save()) {
                    $this->saveLog($activities);
                    $transaction->commit();
                    return $this->json_helper->jsonOutput(0, 'success', null);
                }

                return $this->json_helper->jsonOutput(self::VALIDATION_ERROR_CODE, 'error', $model->getErrors());
            }
        } catch(\Exception $e) {
            $transaction->rollback();
            return $this->json_helper->jsonOutput(self::SYSTEM_ERROR_CODE, 'error', $e->getMessage());
        }
    }
}
