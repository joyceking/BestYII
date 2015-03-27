<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MSubcourse;

/**
 * MSubcourseSearch represents the model behind the search form about `common\models\MSubcourse`.
 */
class MSubcourseSearch extends MSubcourse
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subcourse_id', 'course_id'], 'integer'],
            [['title'], 'safe'],
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
        $query = MSubcourse::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'course_id' => $this->course_id,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'subcourse_id' => $this->subcourse_id,
            'course_id' => $this->course_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
