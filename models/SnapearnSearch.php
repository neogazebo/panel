<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Snapearn;

/**
 * SnapearnSearch represents the model behind the search form about `app\models\Snapearn`.
 */
class SnapearnSearch extends Snapearn
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sna_id', 'sna_acc_id', 'sna_com_id', 'sna_point', 'sna_status', 'sna_upload_date', 'sna_approved_datetime', 'sna_approved_by', 'sna_rejected_datetime', 'sna_rejected_by', 'sna_sem_id', 'sna_cat_id', 'sna_transaction_time'], 'integer'],
            [['sna_receipt_number', 'sna_receipt_date', 'sna_receipt_amount', 'sna_receipt_image', 'sna_com_name'], 'safe'],
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
        $query = Snapearn::find();

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
            'sna_id' => $this->sna_id,
            'sna_acc_id' => $this->sna_acc_id,
            'sna_com_id' => $this->sna_com_id,
            'sna_point' => $this->sna_point,
            'sna_status' => $this->sna_status,
            'sna_upload_date' => $this->sna_upload_date,
            'sna_approved_datetime' => $this->sna_approved_datetime,
            'sna_approved_by' => $this->sna_approved_by,
            'sna_rejected_datetime' => $this->sna_rejected_datetime,
            'sna_rejected_by' => $this->sna_rejected_by,
            'sna_sem_id' => $this->sna_sem_id,
            'sna_cat_id' => $this->sna_cat_id,
            'sna_transaction_time' => $this->sna_transaction_time,
        ]);

        $query->andFilterWhere(['like', 'sna_receipt_number', $this->sna_receipt_number])
            ->andFilterWhere(['like', 'sna_receipt_date', $this->sna_receipt_date])
            ->andFilterWhere(['like', 'sna_receipt_amount', $this->sna_receipt_amount])
            ->andFilterWhere(['like', 'sna_receipt_image', $this->sna_receipt_image])
            ->andFilterWhere(['like', 'sna_com_name', $this->sna_com_name]);

        return $dataProvider;
    }
}
