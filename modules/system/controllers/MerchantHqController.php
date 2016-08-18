<?php

    namespace app\modules\system\controllers;

    use Yii;
    use yii\filters\VerbFilter;
    use yii\data\ActiveDataProvider;
    use yii\web\Response;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use app\controllers\BaseController;

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
            $op = Yii::$app->request->post('op');
            $id = Yii::$app->request->post('com_id');
            
            switch ($op) 
            {
                case 'add':
                    $model = new Company();
                    $scenario = 'new-hq';
                    break;
                
                case 'edit':
                    $model = Company::findOne($id);
                    $scenario = 'update-hq';
                    break;
            }

            $transaction = Yii::$app->db->beginTransaction();

            try
            {
                $model->setScenario($scenario);

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                if($model->load(Yii::$app->request->post(), '')) 
                {
                    if($model->save()) 
                    {
                        $transaction->commit();
                        return $this->jsonOutput(0, 'success', null);
                    }

                    return $this->jsonOutput(9000, 'error', $model->getErrors());
                }
            }
            catch(\Exception $e)
            {
                $transaction->rollback();
                return $this->jsonOutput(1000, 'error', $e->getMessage());
            }
        }

        public function actionList($id)
        {
            $model = Company::findOne($id);
            //$merchant_children = Company::find()->getChildMerchants($id);
            $all_merchant_children = Company::find()->getAllChildMerchants();

            return $this->render('list', [
                'model' => $model,
                //'merchant_children' => $merchant_children,
                'all_merchant_children' => $all_merchant_children
            ]);
        }
    }
