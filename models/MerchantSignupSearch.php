<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MerchantSignup;

/**
 * MerchantSignupSearch represents the model behind the search form about `app\models\MerchantSignup`.
 */
class MerchantSignupSearch extends MerchantSignup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mer_bussines_type_retail', 'mer_bussines_type_service', 'mer_bussines_type_franchise', 'mer_bussines_type_pro_services', 'mer_office_phone', 'mer_office_fax', 'mer_multichain', 'mer_preferr_comm_mail', 'mer_preferr_comm_email', 'mer_preferr_comm_mobile_phone', 'mer_applicant_acknowledge', 'created_date', 'updated_date'], 'integer'],
            [['mer_bussines_name', 'mer_company_name', 'mer_bussiness_description', 'mer_address', 'mer_post_code', 'mer_website', 'mer_multichain_file', 'mer_login_email', 'mer_pic_name', 'mer_contact_phone', 'mer_contact_mobile', 'mer_contact_email', 'mer_agent_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MerchantSignup::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'mer_bussines_type_retail' => $this->mer_bussines_type_retail,
            'mer_bussines_type_service' => $this->mer_bussines_type_service,
            'mer_bussines_type_franchise' => $this->mer_bussines_type_franchise,
            'mer_bussines_type_pro_services' => $this->mer_bussines_type_pro_services,
            'mer_office_phone' => $this->mer_office_phone,
            'mer_office_fax' => $this->mer_office_fax,
            'mer_multichain' => $this->mer_multichain,
            'mer_preferr_comm_mail' => $this->mer_preferr_comm_mail,
            'mer_preferr_comm_email' => $this->mer_preferr_comm_email,
            'mer_preferr_comm_mobile_phone' => $this->mer_preferr_comm_mobile_phone,
            'mer_applicant_acknowledge' => $this->mer_applicant_acknowledge,
            'created_date' => $this->created_date,
            'updated_date' => $this->updated_date,
        ]);

        $query->andFilterWhere(['like', 'mer_bussines_name', $this->mer_bussines_name])
            ->andFilterWhere(['like', 'mer_company_name', $this->mer_company_name])
            ->andFilterWhere(['like', 'mer_bussiness_description', $this->mer_bussiness_description])
            ->andFilterWhere(['like', 'mer_address', $this->mer_address])
            ->andFilterWhere(['like', 'mer_post_code', $this->mer_post_code])
            ->andFilterWhere(['like', 'mer_website', $this->mer_website])
            ->andFilterWhere(['like', 'mer_multichain_file', $this->mer_multichain_file])
            ->andFilterWhere(['like', 'mer_login_email', $this->mer_login_email])
            ->andFilterWhere(['like', 'mer_pic_name', $this->mer_pic_name])
            ->andFilterWhere(['like', 'mer_contact_phone', $this->mer_contact_phone])
            ->andFilterWhere(['like', 'mer_contact_mobile', $this->mer_contact_mobile])
            ->andFilterWhere(['like', 'mer_contact_email', $this->mer_contact_email])
            ->andFilterWhere(['like', 'mer_agent_code', $this->mer_agent_code]);

        return $dataProvider;
    }
}
