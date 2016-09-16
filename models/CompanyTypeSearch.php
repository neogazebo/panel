<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CompanyType;

/**
 * CompanyTypeSearch represents the model behind the search form about `app\models\CompanyType`.
 */
class CompanyTypeSearch extends CompanyType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['com_type_id', 'com_type_multiple_point', 'com_type_max_point', 'com_type_created_by', 'com_type_created_date', 'com_type_updated_date', 'com_type_deleted_date'], 'integer'],
            [['com_type_name'], 'safe'],
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
        $query = CompanyType::find();

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
            'com_type_id' => $this->com_type_id,
            'com_type_multiple_point' => $this->com_type_multiple_point,
            'com_type_max_point' => $this->com_type_max_point,
            'com_type_created_by' => $this->com_type_created_by,
            'com_type_created_date' => $this->com_type_created_date,
            'com_type_updated_date' => $this->com_type_updated_date,
            'com_type_deleted_date' => $this->com_type_deleted_date,
        ]);

        $query->andFilterWhere(['like', 'com_type_name', $this->com_type_name]);

        return $dataProvider;
    }
}
