<?php

namespace app\modules\system\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;
use app\modules\system\processors\merchant_hq\MerchantHqSaveProcessor;
use app\modules\system\processors\merchant_hq\MerchantHqChildrenSaveProcessor;
use app\modules\system\processors\merchant_hq\MerchantHqChildrenSearchProcessor;
use app\modules\system\processors\merchant_hq\MerchantHqDeleteProcessor;
use app\models\Company;

class MerchantHqController extends BaseController
{
    public function actionIndex()
    {
        $company = new Company();
        $data = Company::find()->getParentMerchants();

        $dataProvider = new ActiveDataProvider([
            'query' => $data,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categories' => $company->categoryListData
        ]);
    }

    public function actionOp()
    {
        $processor = new MerchantHqSaveProcessor();
        return $processor->process();
    }

    public function actionList($id)
    {
        $model = Company::findOne($id);
        $merchant_children = Company::find()->getChildMerchants($id)->asArray()->all();
        //$all_merchant_children = Company::find()->getAllChildMerchants();

        return $this->render('list', [
            'model' => $model,
            'merchant_children' => $merchant_children,
            //'all_merchant_children' => $all_merchant_children
        ]);
    }

    public function actionSearch()
    {
        $processor = new MerchantHqChildrenSearchProcessor();
        return $processor->process();
    }

    public function actionSaveChild()
    {
        $processor = new MerchantHqChildrenSaveProcessor();
        return $processor->process();
    }

    public function actionDelete()
    {
        $processor = new MerchantHqDeleteProcessor();
        return $processor->process();
    }
}
