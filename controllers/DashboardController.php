<?php
namespace app\controllers;

use Yii;
use app\controllers\BaseController;
/**
 *
 */
class DashboardController extends BaseController
{

    public function actionIndex()
    {
        $model = $this->findModel();
        return $this->render('dashboard',[
            'model' => $model
        ]);
    }

    protected function findModel()
    {
        
    }
}
