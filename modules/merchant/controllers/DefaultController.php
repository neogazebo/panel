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
    	if (Yii::$app->request->isAjax) {
			$model = Company::find()->searchExistingMerchant();
    	    $out = [];
		    foreach ($model as $d) {
		        $out[] = [
		        	'id' => $d->com_id,
		        	'value' => $d->com_name
		        ];
		    }
		    echo \yii\helpers\Json::encode($out);
    	}
	}
}
