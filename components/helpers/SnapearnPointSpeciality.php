<?php
namespace app\components\helpers;

/**
* Author : ilham Fauzi
* Mail   : ilham@ebizu.com
*/
use Yii;
use app\models\CompanySpeciality;

class SnapearnPointSpeciality
{

	public function getActivePoint($conditions)
	{
		$com_speciality = CompanySpeciality::find()->with('promo')->where('com_spt_id = :com_speciality',[ ':com_speciality' => $conditions])->asArray()->one();
		if ($com_speciality['promo']) {
			return $com_speciality['promo']['spt_promo_point'];
		}
		return $com_speciality['com_spt_multiple_point'];
	}
}
