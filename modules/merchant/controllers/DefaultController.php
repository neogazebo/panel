<?php

namespace app\modules\merchant\controllers;

use Yii;
use yii\web\Controller;
use app\controllers\BaseController;
use app\models\Company;

/**
 * Default controller for the `merchant` module
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
    	if (Yii::$app->request->isAjax){
		    	// \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				$model = Company::find()->searchExistingMerchant();
	    	    $out = [];
			    foreach ($model as $d) {
			        $out[] = ['id' => $d->com_id,'value' => $d->com_name];
			    }
			    echo \yii\helpers\Json::encode($out);
    		// 	$model = Company::find()->searchExistingMerchant();
		    	// $out = ['results' => ['id' => '', 'text' => '']];
		    	// foreach ($model as $value) {
		    	// 	$out['results'] = ['id' => $value->id, 'text' => $value->com_name];
		    	// }
		    // if (!is_null($q)) {
		    //     $query = new Query;
		    //     $query->select('com_id AS id, com_name AS text')
		    //         ->from('tbl_company')
		    //         ->where(['like', 'com_name', $q])
		    //         ->limit(20);
		    //     $command = $query->createCommand();
		    //     $data = $command->queryAll();
		    //     $out['results'] = array_values($data);
		    // }
		    // elseif ($id > 0) {
		    //     $out['results'] = ['id' => $id, 'text' => Company::findOne($id)->com_name];
		    // }
    		// 	$out['results'] = array_values($data);
		    // return $out;
    	}
	}
}
