<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorkingTime;

/**
 * SearchWorkingTime represents the model behind the search form about `app\models\WorkingTIme`.
 */
class SearchWorkingTime extends WorkingTIme
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wrk_id', 'wrk_type', 'wrk_by', 'wrk_param_id', 'wrk_created', 'wrk_updated'], 'integer'],
            [['wrk_start', 'wrk_end', 'wrk_time'], 'number'],
            [['wrk_description'], 'safe'],
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
        $query = WorkingTime::find()->where('wrk_end IS NOT NULL AND wrk_time IS NOT NULL GROUP BY wrk_by ');

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
            'wrk_id' => $this->wrk_id,
            'wrk_type' => $this->wrk_type,
            'wrk_by' => $this->wrk_by,
            'wrk_param_id' => $this->wrk_param_id,
            'wrk_start' => $this->wrk_start,
            'wrk_end' => $this->wrk_end,
            'wrk_time' => $this->wrk_time,
            'wrk_created' => $this->wrk_created,
            'wrk_updated' => $this->wrk_updated,
        ]);

        $query->andFilterWhere(['like', 'wrk_description', $this->wrk_description]);

        return $dataProvider;
    }
}
