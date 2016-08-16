<?php

    namespace app\modules\merchant_hq\controllers;

    use Yii;
    use yii\filters\VerbFilter;
    use yii\data\ActiveDataProvider;
    use yii\web\Response;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use app\controllers\BaseController;

    use app\models\SnapearnGroup;
    use app\models\Company;

    class DefaultController extends BaseController
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
    }
