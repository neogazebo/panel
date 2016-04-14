<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class TestController extends Controller
{
	public function actionIndex()
	{
//		echo Yii::$app->params['EPAY_URL'];
            echo "oke gud ";
	}
}