<?php

namespace app\modules\mobile\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\MobilePulsaTopup;

/**
 * Description of LogController
 *
 * @author Elvis Sonatha <elvis@ebizu.com>
 */
class LogController extends MpController
{
    public function actionIndex()
    {
        $model = MobilePulsaTopup::find()->with(['partner', 'member'])->list;
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);        
    }
}
