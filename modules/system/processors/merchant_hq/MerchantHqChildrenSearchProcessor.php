<?php

namespace app\modules\system\processors\merchant_hq;

use Yii;
use app\modules\bases\BaseProcessor;
use app\models\Company;

class MerchantHqChildrenSearchProcessor extends BaseProcessor
{
    public function process()
    {
        try {
            $keyword = Yii::$app->request->post('keyword');
            $hq_id = Yii::$app->request->post('hq_id');

            $data = Company::find()->searchMerchant($keyword, $hq_id)->asArray()->all();
            $output = Yii::$app->controller->renderPartial('partials/search_result', ['data' => $data]);
            return $this->json_helper->jsonOutput(0, 'success', null, $output);
        } catch(\Exception $e) {
            return $this->json_helper->jsonOutput(self::SYSTEM_ERROR_CODE, 'error', $e->getMessage());
        }
    }
}
