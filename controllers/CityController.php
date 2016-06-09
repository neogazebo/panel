<?php

namespace app\controllers;

use Yii;
use app\models\City;

class CityController extends BaseController
{
    public function actionList($q = null, $id = null)
    {
    	if (Yii::$app->request->isAjax) {
    		$search = Yii::$app->request->get('q');
	        $keyword = preg_split("/[\s,]+/", $search);

			$model = City::find();
	        $model->select('cit_id, cit_name');
	        foreach ($keyword as $key)
	            $model->where('cit_name LIKE :get', [':get' => '%' . $key . '%']);

	        $model->orderBy('cit_name');
	        $model->limit(10);
    	    $out = [];
		    foreach ($model as $d) {
		        $out[] = [
		        	'id' => $d->com_id,
		        	'value' => $d->cit_name . ', ' . $d->region->reg_name . ', ' . $d->country->cny_name
		        ];
		    }
		    echo \yii\helpers\Json::encode($out);
    	}
	}

}
