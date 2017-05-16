<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Business;

/**
 * BusinessSearch represents the model behind the search form about `app\models\Business`.
 */
class BusinessSearch extends Business
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'user_id',
                    'zip_id',
                    'is_home'
                ],
                'integer'
            ],
            [
                [
                    'name',
                    'address',
                    'suite',
                    'phone',
                    'website',
                    'contact_email',
                    'description',
                    'zip_notice',
                    'yelp_url'
                ],
                'safe'
            ],
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
        $query = Business::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_ASC]]
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
            'user_id' => $this->user_id,
            'zip_id' => $this->zip_id,
            'is_home' => $this->is_home,
        ]);

        $query->andFilterWhere([
            'like',
            'name',
            $this->name
        ])
            ->andFilterWhere([
                'like',
                'address',
                $this->address
            ])
            ->andFilterWhere([
                'like',
                'suite',
                $this->suite
            ])
            ->andFilterWhere([
                'like',
                'phone',
                $this->phone
            ])
            ->andFilterWhere([
                'like',
                'website',
                $this->website
            ])
            ->andFilterWhere([
                'like',
                'contact_email',
                $this->contact_email
            ])
            ->andFilterWhere([
                'like',
                'description',
                $this->description
            ])
            ->andFilterWhere([
                'like',
                'zip_notice',
                $this->zip_notice
            ])
            ->andFilterWhere([
                'like',
                'yelp_url',
                $this->yelp_url
            ]);

        return $dataProvider;
    }
}
