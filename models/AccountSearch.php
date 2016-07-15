<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Account;

/**
 * AccountSearch represents the model behind the search form about `app\models\Account`.
 */
class AccountSearch extends Account
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['acc_facebook_id',  'acc_updated_datetime', 'acc_status', 'acc_tmz_id', 'acc_birthdate', 'acc_gender'], 'integer'],
            [['acc_facebook_email', 'acc_facebook_graph', 'acc_google_id', 'acc_google_email', 'acc_google_token', 'acc_screen_name', 'acc_cty_id', 'acc_photo', 'acc_address'], 'safe'],
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
        $query = Account::find();

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
            'acc_id' => $this->acc_id,
            'acc_facebook_id' => $this->acc_facebook_id,
            'acc_created_datetime' => $this->acc_created_datetime,
            'acc_updated_datetime' => $this->acc_updated_datetime,
            'acc_status' => $this->acc_status,
            'acc_tmz_id' => $this->acc_tmz_id,
            'acc_birthdate' => $this->acc_birthdate,
            'acc_gender' => $this->acc_gender,
        ]);

        $query->andFilterWhere(['like', 'acc_facebook_email', $this->acc_facebook_email])
            ->andFilterWhere(['like', 'acc_facebook_graph', $this->acc_facebook_graph])
            ->andFilterWhere(['like', 'acc_google_id', $this->acc_google_id])
            ->andFilterWhere(['like', 'acc_google_email', $this->acc_google_email])
            ->andFilterWhere(['like', 'acc_google_token', $this->acc_google_token])
            ->andFilterWhere(['like', 'acc_screen_name', $this->acc_screen_name])
            ->andFilterWhere(['like', 'acc_cty_id', $this->acc_cty_id])
            ->andFilterWhere(['like', 'acc_photo', $this->acc_photo])
            ->andFilterWhere(['like', 'acc_address', $this->acc_address]);

        return $dataProvider;
    }
}
