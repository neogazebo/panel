<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class TestController extends Controller
{
	public function actionIndex()
	{
	    $path = Yii::getAlias('@app/');
	    sleep(60);
	    exec('touch '.$path.'curut.txt');
	    
//		echo Yii::$app->params['EPAY_URL'];
            echo "oke gud ";
	}
}