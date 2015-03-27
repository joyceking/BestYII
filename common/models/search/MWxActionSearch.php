<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MWxAction;

/**
 * MWxActionSearch represents the model behind the search form about `common\models\MWxAction`.
 */
class MWxActionSearch extends MWxAction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'keyword', 'type', 'action'], 'safe'],
            [['wx_action_id'], 'integer'],
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
        $query = MWxAction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'wx_action_id' => $this->wx_action_id,
        ]);

        $query->andFilterWhere(['like', 'gh_id', $this->gh_id])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'action', $this->action]);

        return $dataProvider;
    }
}
