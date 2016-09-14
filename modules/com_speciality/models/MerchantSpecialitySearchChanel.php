<?php

namespace app\modules\com_speciality\models;

use Yii;
use app\models\Company;
use app\models\CompanySpeciality;
use app\modules\bases\BaseProcessor;

class MerchantSpecialitySearchChanel extends BaseProcessor
{
    public function process()
    {
        // try {
            $keyword = Yii::$app->request->post('keyword');
            $spt = Yii::$app->request->post('com_speciality');
            $country = CompanySpeciality::find()->with('country')->where('com_spt_id = :id',[
                    ':id' => $spt
                ])->one();
            $cty = $country->country->cty_currency_name_iso3;
            $data = Company::find()->searchMerchantSpeciality($keyword, $spt, $cty)->asArray()->all();
            $output = Yii::$app->controller->renderPartial('partials/search_result', ['data' => $data]);
            return $this->json_helper->jsonOutput(0, 'success', null, $output);
        // } catch(\Exception $e) {
        //     return $this->json_helper->jsonOutput(self::SYSTEM_ERROR_CODE, 'error', $e->getMessage());
        // }
    }
}
