<?php

namespace app\modules\rbac\controllers;

use yii\web\Controller;
use app\models\AuthItem;

/**
 * Default controller for the `rbac` module
 */
class IndexController extends Controller
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
    	// if ( $model->load(Yii::$app->request->post () )) {

    	// }

    	return $this->render('create-role',[
    			'model' => $model,
			]);	
    }
}
