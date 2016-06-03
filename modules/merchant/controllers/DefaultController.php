<?php

namespace app\modules\merchant\controllers;

use yii\web\Controller;

/**
 * Default controller for the `merchant` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList($q = null, $id = null) {
	    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    $out = ['results' => ['id' => '', 'text' => '']];
	    if (!is_null($q)) {
	        $query = new Query;
	        $query->select('com_id AS id, com_name AS text')
	            ->from('tbl_company')
	            ->where(['like', 'com_name', $q])
	            ->limit(20);
	        $command = $query->createCommand();
	        $data = $command->queryAll();
	        $out['results'] = array_values($data);
	    }
	    elseif ($id > 0) {
	        $out['results'] = ['id' => $id, 'text' => Company::findOne($id)->com_name];
	    }
	    return $out;
	}
}
