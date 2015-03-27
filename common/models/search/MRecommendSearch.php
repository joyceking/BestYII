<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MRecommend;

/**
 * MRecommendSearch represents the model behind the search form about `common\models\MRecommend`.
 */
class MRecommendSearch extends MRecommend
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recommend_id'], 'integer'],
            [['gh_id', 'openid', 'name', 'mobile', 'status', 'create_time'], 'safe'],
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
        $query = MRecommend::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'recommend_id' => $this->recommend_id,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
