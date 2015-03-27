<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MCourse;

/**
 * MCourseSearch represents the model behind the search form about `common\models\MCourse`.
 */
class MCourseSearch extends MCourse
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'is_delete'], 'integer'],
            [['title', 'des'], 'safe'],
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
        $query = MCourse::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'course_id' => $this->course_id,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
