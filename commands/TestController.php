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
	    exec('touch '.$path.'curut4.txt');
	    
//		echo Yii::$app->params['EPAY_URL'];
            echo "oke gud ";
	}
	
	public function actionIndexes()
	{
	    $path = Yii::getAlias('@app/');
	    exec('touch '.$path.'curut2.txt');
	    
//		echo Yii::$app->params['EPAY_URL'];
            echo "oke gud ";
	}
}