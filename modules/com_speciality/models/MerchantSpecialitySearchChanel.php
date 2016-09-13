<?php

namespace app\modules\system\processors\merchant_hq;

use Yii;
use app\modules\system\processors\bases\BaseProcessor;
use app\models\Company;

class MerchantSpecialitySearchChanel extends BaseProcessor
{
    public function process()
    {
        try {
            $keyword = Yii::$app->request->post('keyword');
            $spt = Yii::$app->request->post('spt');

            $data = Company::find()->searchMerchantSpeciality($keyword, $spt)->asArray()->all();
            $output = Yii::$app->controller->renderPartial('partials/search_result', ['data' => $data]);
            return $this->json_helper->jsonOutput(0, 'success', null, $output);
        } catch(\Exception $e) {
            return $this->json_helper->jsonOutput(self::SYSTEM_ERROR_CODE, 'error', $e->getMessage());
        }
    }
}
