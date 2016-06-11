<?php

namespace app\modules\mall\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\BaseController;
use yii\web\JsExpression;
use app\models\Mall;
use app\models\City;

/**
 * Default controller for the `mall` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList($q = null, $id = null)
    {
    	if (Yii::$app->request->isAjax) {
    		$search = Yii::$app->request->get('q');
	        $keyword = preg_split("/[\s,]+/", $search);

	        $model = Mall::find();
	        $model->select('mal_id, mal_name');
	        foreach ($keyword as $key)
	            $model->where('mal_name LIKE :get', [':get' => '%' . $key . '%',]);
	        $model->orderBy('mal_name');
	        $model->limit(20);

    	    $out = [];
		    foreach ($model as $d) {
		        $out[] = [
		        	'id' => $d->com_id,
		        	'text' => $d->com_name
		        ];
		    }
		    echo \yii\helpers\Json::encode($out);
    	}
    }
}
