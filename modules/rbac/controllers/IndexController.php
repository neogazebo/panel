<?php

namespace app\modules\rbac\controllers;

use Yii;
use yii\web\Controller;
use app\models\AuthItem;
use app\controllers\BaseController;


/**
 * Default controller for the `rbac` module
 */
class IndexController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex ()
    {
        return $this->render ('index');
    }

    public function actionCreate () 
    {
    	$model = new AuthItem ();
        $model->created_by = Yii::$app->user->identity->id;
        // ajax validation
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post ())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

    	if ( $model->load(Yii::$app->request->post ())) {
            if($model->save()){
                $this->setMessage('save', 'success', 'Role "'.$model->name.'" sucess created!');
                return $this->redirect('index');
            }else{
                $this->setMessage('save', 'error', General::extactErrorModel($model->getErrors()));
                return $this->redirect('index');
            }
    	}

    	return $this->renderAjax('create-role',[
    			'model' => $model,
			]);	
    }
}
