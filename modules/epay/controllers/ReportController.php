<?php

namespace app\modules\epay\controllers;

use Yii;

/**
* 
*/
class ReportController extends EpaybaseController
{
	
	public function actionIndex()
	{
		return $this->render('index');
	}
}