<?php

/*
 * author by elvis@ebizu.com
*/

namespace app\components\helpers;

use Yii;
use app\models\SnapEarn;

class WorkingTime
{
	public static function SnapEarnStart($id)
	{
		$model = SnapEarn::findOne($id);
		$model->sna_work_start_time = time();
		$model->save(false);
	}

	public static function length($start, $end)
	{
		return gmdate('H:i:s', (int)$end - (int)$start);
	}

	public static function SnapEarnEnd($id)
	{
		$model = SnapEarn::findOne($id);
		$model->sna_work_end_time = time();
		$model->sna_work_id = Yii::$app->user->id;
		$model->save(false);
	}

	public static function SnapEarnCancel($id)
	{
		$model = SnapEarn::findOne($id);
		$model->sna_work_start_time = '';
		$model->save(false);
	}
}
