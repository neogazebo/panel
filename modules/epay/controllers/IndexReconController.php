<?php

namespace app\modules\controller;

use Yii;

/**
* 
*/
class IndexReconController extends EpayBaseController
{
	
	public function actionReportRecon()
	{
		return $this->render('recon/index');
	}
}