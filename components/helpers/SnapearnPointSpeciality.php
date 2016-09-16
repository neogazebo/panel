<?php
namespace app\components\helpers;

/**
* Author : ilham Fauzi
* Mail   : ilham@ebizu.com
*/
use Yii;
use app\models\Account;
use app\models\Company;
use app\models\CompanySpeciality;
use app\models\CompanyType;
use app\models\Country;
use app\models\MasterCountry;
use app\models\SnapEarn;

class SnapearnPointSpeciality
{

	public function getActivePoint($conditions = null)
	{
		// get snapearn config detail
		$config = [];
		$snap = SnapEarn::find()
		->where('sna_id = :sna',[
			':sna' => $conditions
			])->asArray()->one();
		$company = $this->getCompanyConfig($snap['sna_com_id']);
	}


	protected function getCompanyConfig($id)
	{
		// get company detail
		$config = [];
		$company = Company::find()
		->where('com_id = :com',[
				':com' => $id
			])->asArray()->one();
		$type = $company['com_speciality'];
		$country = $this->getCompanyByCode('cty_currency_name_iso3',$company['com_currency']);
		$country_id = $country['cty_id'];
		if(!empty($country_id)){
			$data = $this->getSpecialityConfig($type,$country_id);
		}else{
			$data = $this->getSpecialityByType($type);
		}
		return $data;
	}

	public function getType($type)
	{
		$model = CompanyType::find()
		->where('com_type_id = :type',[
				':type' => $type
			])->asArray()->one();
		return $model;
	}

	public function getSpecialityByType($type)
	{	
		$config = [];
		$model = CompanyType::find()
		->select('com_type_multiple_point,com_type_max_point')
		->where('com_type_id = :id',[':id' => $type])->asArray()->one();

		$config['point'] = $model['com_type_multiple_point'];
		$config['max_point'] = $model['com_type_max_point'];
		return $config;
	}

	public function getSpecialityConfig($type = NULL, $cty = NULL)
	
		$data = CompanySpeciality::find()
		->with('type','country','promo')
		->where('com_spt_type_id = :type',[
				':type' => $type
			])
		->andWhere('com_spt_cty_id = :cty',[
				':cty' => $cty
			])->asArray()->all();
		$model = $this->getFinalConfig($data);
		return $model;
	}

	public function getFinalConfig($params)
	{
		$model = $params;
		$config = [];

		$config['point'] = NULL;
		$config['max_point'] = NULL;
		$config['day_promo'] = NULL;
		$config['start'] = NULL;
		$config['end'] = NULL;

		if ($model[0]['promo']) {

			if($model[0]['promo']['spt_promo_multiple_point'])
				$config['point'] = $model[0]['promo']['spt_promo_multiple_point'];
			if($model[0]['promo']['spt_promo_max_point'])
				$config['max_point'] = $model[0]['promo']['spt_promo_max_point'];
			if($model[0]['promo']['spt_promo_day_promo'])
				$config['day_promo'] = $model[0]['promo']['spt_promo_day_promo'];

			$config['start'] = $model[0]['promo']['spt_promo_start_date'];
			$config['end'] = $model[0]['promo']['spt_promo_end_date'];
		}

		if (!$config['point'] && ($model[0]['com_spt_multiple_point']))
			$config['point'] = $model[0]['com_spt_multiple_point'];

		if(!$config['max_point'] && ($model[0]['com_spt_max_point']))
			$config['max_point'] = $model[0]['com_spt_max_point'];

		if (!$config['point'] && ($model[0]['type']['com_type_multiple_point']))
			$config['point'] = $model[0]['type']['com_type_multiple_point'];

		if(!$config['max_point'] && ($model[0]['type']['com_type_max_point']))
			$config['max_point'] = $model[0]['type']['com_type_max_point'];

		return $config;
	}

	public function getCompanyByCode($findIt = null, $params)
	{
		$model = Country::find()
		->select('cty_id')
		->where($findIt.' = :code',[
				':code' => $params
			])->asArray()->one();
		return $model;
	}

	public function getCompanyById($findIt = null, $params)
	{
		$model = MasterCountry::find()
		->select('cny_shortcode2')
		->where($findIt.' = :code',[
				':code' => $params
			])->asArray()->one();
		return $model;
	}
}
