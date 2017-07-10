<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RedemptionReference;

/**
 * SearchRedemptionReference represents the model behind the search form about `app\models\RedemptionReference`.
 */
class SearchRedemptionReference extends RedemptionReference
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rdr_id', 'rdr_acc_id', 'rdr_vou_id', 'rdr_vou_type'], 'integer'],
            [['rdr_msisdn', 'rdr_vod_code'], 'safe'],
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
        $query = RedemptionReference::find()->orderBy('rdr_datetime DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rdr_id' => $this->rdr_id,
            'rdr_acc_id' => $this->rdr_acc_id,
            'rdr_vou_id' => $this->rdr_vou_id,
            'rdr_vou_type' => $this->rdr_vou_type,
            'rdr_status' => $this->rdr_status,
            'rdr_datetime' => $this->rdr_datetime,
            'rdr_vou_value' => $this->rdr_vou_value,
        ]);

        $query->andFilterWhere(['like', 'rdr_msisdn', $this->rdr_msisdn])
            ->andFilterWhere(['like', 'rdr_vod_sn', $this->rdr_vod_sn])
            ->andFilterWhere(['like', 'rdr_vod_code', $this->rdr_vod_code])
            ->andFilterWhere(['like', 'rdr_name', $this->rdr_name]);

        return $dataProvider;
    }
}
