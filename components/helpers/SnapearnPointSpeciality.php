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
		$config = [];
		// get snapearn detail
		$snap = SnapEarn::find()
		->where('sna_id = :sna',[
			':sna' => $conditions
			])->asArray()->one();
		
		$company = $this->getCompanyConfig($snap['sna_com_id']);
		// $from_company = $from_company['com_country']['cty_id'];

		$account = Account::find()
		->where('acc_id = :acc',[
			':acc' => $snap['sna_acc_id']
			])->asArray()->one();
		// $from_account['acc_country'] = $this->getCompany($account['acc_cty_id']);

		// if ($from_company['com_country'] != $from_account['acc_country']) {
		// 	$not_same['reason'] = "Country of the user and the merchant does not match !!";
		// 	$not_same['user_country'] = $this->getCompany($from_account['acc_country']);
		// }
		// $speciality = $this->getSpeciality($from_company['com_type'],$from_company['com_country']);
		
		// if ($com_speciality['promo']) {
		// 	return $com_speciality['promo']['spt_promo_point'];
		// }
		// return $com_speciality['com_spt_multiple_point'];
	}


	protected function getCompanyConfig($id)
	{
		// get company detail
		$company_conf = [];
		$company_conf['com_type'] = NULL;
		$company_conf['com_country'] = NULL;

		$company = Company::find()
		->where('com_id = :com',[
				':com' => $id
			])->asArray()->one();

		// get company speciality type
		$company_conf['com_type'] = $this->getCompanyType($company['com_speciality']);
		// get com_country using com_currecny
		$company_conf['com_country']  = $this->getCompanyCode($company['com_currency']);

		// if company country empty using com_currency do this
		if (empty($company_conf['com_country'])) {
			if (!empty($company['com_country_id'])) {
				$master_country = MasterCountry::find()
					->where('cny_id = :cny_id',[
						':cny_id' => $company['com_country_id']
					])->asArray()->one();
				// get com country using country short code 2 on table copuntry
				$company_conf['com_country'] = $this->getCompany($master_country['cny_shortcode2']);
			}
		}

		return $company_conf;
	}

	public function getSpeciality($type, $cty)
	{
		$com_speciality = CompanySpeciality::find()
			->with('promo','type','country')
			->where('com_spt_type_id = :com_type AND com_spt_cty_id = :cty_id',[ 
				':com_type' => $type,
				':cty_id' => $cty
			])->asArray()->all();
		return $com_speciality;
	}

	public function getCompanyType($params)
	{
		// get company type id
		$type = CompanyType::find()
		->where('com_type_id = :com_type',[
				':com_type' => $params
			])->asArray()->one();
		return $type;
	}

	public function getCompanyCode($params)
	{
		// get country id using country short code 3
		$country = Country::find()
		->where('cty_currency_name_iso3 = :code',[
				':code' => $params
			])->asArray()->one();
		return $country;
	}

	public function getCompany($params)
	{
		// get country id using country short code 2
		$country = Country::find()
		->where('cty_short_code = :acc_cty_id',[
				':acc_cty_id' => $params
			])->asArray()->one();
		return $country;
	}
}
