<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\user;

/**
 * userSearch represents the model behind the search form about `app\models\user`.
 */
class userSearch extends user
{
    public $min_year;
    public $max_year;
    public $userFullName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['min_year', 'max_year','userFullName' ], 'safe'],
            [['id', 'status', 'created_at', 'updated_at', 'online'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'gender', 'first_name', 'last_name', 'bd_date', 'country', 'status_text'], 'safe'],
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
        $query = user::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            //'bd_date' => $this->bd_date,
            'online' => $this->online,
        ]);

        $query->andFilterWhere([
                'or',
                ['like', 'first_name', $this->userFullName],
                ['like', 'last_name', $this->userFullName],
            ])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['and',
                ['>=', '((UNIX_TIMESTAMP()-UNIX_TIMESTAMP(`bd_date`))/60/60/24/365)', $this->min_year],
                ['<=', '((UNIX_TIMESTAMP()-UNIX_TIMESTAMP(`bd_date`))/60/60/24/365)', $this->max_year]])
            //->andFilterWhere(['like', 'first_name,last_name', $this->first_name])
           // ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}
