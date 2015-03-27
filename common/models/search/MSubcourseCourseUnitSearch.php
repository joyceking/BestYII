<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MSubcourseCourseUnit;

/**
 * MSubcourseCourseUnitSearch represents the model behind the search form about `common\models\MSubcourseCourseUnit`.
 */
class MSubcourseCourseUnitSearch extends MSubcourseCourseUnit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subcourse_course_unit_id', 'subcourse_id', 'course_unit_id'], 'integer'],
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
        $query = MSubcourseCourseUnit::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'subcourse_id' => $this->subcourse_id,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'subcourse_course_unit_id' => $this->subcourse_course_unit_id,
            'subcourse_id' => $this->subcourse_id,
            'course_unit_id' => $this->course_unit_id,
        ]);

        return $dataProvider;
    }
}
