<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MKeyword;

/**
 * MKeywordSearch represents the model behind the search form about `common\models\MKeyword`.
 */
class MKeywordSearch extends MKeyword
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword_id'], 'integer'],
            [['keyword', 'value', 'create_time', 'update_time'], 'safe'],
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
        $query = MKeyword::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'keyword_id' => $this->keyword_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
