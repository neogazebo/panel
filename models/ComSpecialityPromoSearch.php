<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ComSpecialityPromo;

/**
 * ComSpecialityPromoSearch represents the model behind the search form about `app\models\ComSpecialityPromo`.
 */
class ComSpecialityPromoSearch extends ComSpecialityPromo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spt_promo_id', 'spt_promo_com_spt_id', 'spt_promo_point', 'spt_promo_created_by', 'spt_promo_start_date', 'spt_promo_end_date', 'spt_promo_created_date'], 'integer'],
            [['spt_promo_description'], 'safe'],
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
        $query = ComSpecialityPromo::find();

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
            'spt_promo_id' => $this->spt_promo_id,
            'spt_promo_com_spt_id' => $this->spt_promo_com_spt_id,
            'spt_promo_point' => $this->spt_promo_point,
            'spt_promo_created_by' => $this->spt_promo_created_by,
            'spt_promo_start_date' => $this->spt_promo_start_date,
            'spt_promo_end_date' => $this->spt_promo_end_date,
            'spt_promo_created_date' => $this->spt_promo_created_date,
        ]);

        $query->andFilterWhere(['like', 'spt_promo_description', $this->spt_promo_description]);

        return $dataProvider;
    }
}
