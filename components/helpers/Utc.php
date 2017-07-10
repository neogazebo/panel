<?php

/*
 * author by elvis@ebizu.com
*/

namespace app\components\helpers;

use Yii;

class Utc
{
	public static function convert($date)
	{
		$gmt = 0;
		$idUtc = 7 * 60 * 60;
		$myUtc = 8 * 60 * 60;
		switch(Yii::$app->user->identity->country) {
			case 'ID':
				$gmt = $date + $idUtc;
				break;
			case 'MYR':
				$gmt = $date + $myUtc;
				break;
		}
		return $gmt;
	}

	public static function getNow()
	{
		$time = Yii::$app->db
	        ->createCommand("SELECT UNIX_TIMESTAMP() AS time")
            ->queryScalar();
        return intval($time);
    }

	public static function getTime($date)
	{
		$time = Yii::$app->db
	        ->createCommand("SELECT UNIX_TIMESTAMP(:time) AS time")
            ->bindValue(':time', $date)
            ->queryScalar();
	    return intval($time);
	}

	public static function deconvert($date)
	{
		$gmt = 0;
		$idUtc = 7 * 60 * 60;
		$myUtc = 8 * 60 * 60;
		switch(Yii::$app->user->identity->country) {
			case 'ID':
				$gmt = $date - $idUtc;
				break;
			case 'MYR':
				$gmt = $date - $myUtc;
				break;
		}
		return $gmt;
	}
}
