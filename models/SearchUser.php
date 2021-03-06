<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\User;

/**
 * SearchUser represents the model behind the search form about `app\models\User`.
 */
class SearchUser extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role', 'status', 'create_time', 'update_time', 'type', 'level', 'mall', 'mall_role_id', 'superuser'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'country'], 'safe'],
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
        $query = User::find()->select([
            '{{tbl_admin_user}}.*',
            '{{auth_assignment}}.item_name as role_name'
        ])->where('type = 1');

        // add conditions that should always apply here

        $query->joinWith(['roles']);

        $query->orderBy('id DESC');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'role' => $this->role,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'type' => $this->type,
            'level' => $this->level,
            'mall' => $this->mall,
            'mall_role_id' => $this->mall_role_id,
            'superuser' => $this->superuser,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'tbl_admin_user.email', $this->email])
            ->andFilterWhere(['like', 'country', $this->country]);

        /*
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);
        */

        $dataProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->getRawSql(),
            'sort' => false,
            'key' => 'id',
            'totalCount' => $query->count(),
        ]);
        
        return $dataProvider;
    }
}
