<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Promo;

/**
 * PromoSearch represents the model behind the search form about `app\models\Promo`.
 */
class PromoSearch extends Promo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'serv_id', 'discount', 'nco', 'combine', 'active'], 'integer'],
            [['price'], 'number'],
            [['start', 'end', 'terms'], 'safe'],
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
        $query = Promo::find();

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
            'serv_id' => $this->serv_id,
            'price' => $this->price,
            'discount' => $this->discount,
            'nco' => $this->nco,
            'combine' => $this->combine,
            'start' => $this->start,
            'end' => $this->end,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'terms', $this->terms]);

        return $dataProvider;
    }
}
