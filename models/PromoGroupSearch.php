<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PromoGroup;

/**
 * PromoGroupSearch represents the model behind the search form about `app\models\PromoGroup`.
 */
class PromoGroupSearch extends PromoGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'promo_id', 'menu_id', 'cat_id'], 'integer'],
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
        $query = PromoGroup::find();

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
            'promo_id' => $this->promo_id,
            'cat_id' => $this->cat_id,
        ]);

        return $dataProvider;
    }
}