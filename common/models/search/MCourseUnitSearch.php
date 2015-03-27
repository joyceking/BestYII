<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MCourseUnit;

class MCourseUnitSearch extends MCourseUnit
{
    public function rules()
    {
        return [
            [['course_unit_id', 'course_id', 'minutes', 'sort_order'], 'integer'],
            [['title', 'des', 'prepare', 'caution'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MCourseUnit::find()->where(['course_id'=>$params['course_id']])->orderBy(['course_unit_id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'course_unit_id' => $this->course_unit_id,
            'course_id' => $this->course_id,
            'minutes' => $this->minutes,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'prepare', $this->prepare])
            ->andFilterWhere(['like', 'caution', $this->caution]);

        return $dataProvider;
    }
}
