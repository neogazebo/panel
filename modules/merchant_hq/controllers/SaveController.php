<?php

    namespace app\modules\merchant_hq\controllers;

    use Yii;
    use yii\filters\VerbFilter;
    use yii\data\ActiveDataProvider;
    use yii\web\Response;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use app\controllers\BaseController;

    use app\models\Company;

    class SaveController extends BaseController
    {
        private $model;

        public function __construct($id, $module, Company $model)
        {
            $this->model = new $model();
            parent::__construct($id, $module);
        }
        
        public function actionIndex()
        {
            //$output = $this->renderPartial('/partials/output');

            $this->model->setScenario('new-hq');

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($this->model->load(Yii::$app->request->post(), '')) 
            {
                if($this->model->save()) 
                {
                    return $this->jsonOutput(0, 'success', null);
                }

                return $this->jsonOutput(1, 'error', $this->model->getErrors());
            }
            
        }

        public function jsonOutput($code, $status, $message)
        {
            return [
                'error' => $code,
                'status' => $status,
                'message' => $message
            ];
        }
    }
