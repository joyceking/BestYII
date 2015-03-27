<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MReserve;

/**
 * MReserveSearch represents the model behind the search form about `common\models\MReserve`.
 */
class MReserveSearch extends MReserve
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reserve_id', 'school_branch_id', 'course_id', 'age'], 'integer'],
            [['name', 'sex', 'mobile', 'memo'], 'safe'],
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
        $query = MReserve::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'reserve_id' => $this->reserve_id,
            'school_branch_id' => $this->school_branch_id,
            'course_id' => $this->course_id,
            'age' => $this->age,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'memo', $this->memo]);

        return $dataProvider;
    }
}
