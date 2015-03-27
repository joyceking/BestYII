<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MStudentSubcourse;

/**
 * MStudentSubcourseSearch represents the model behind the search form about `common\models\MStudentSubcourse`.
 */
class MStudentSubcourseSearch extends MStudentSubcourse
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'student_id', 'subcourse_id', 'score'], 'integer'],
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
        $query = MStudentSubcourse::find()->where(['student_id'=>$params['student_id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'student_id' => $this->student_id,
            'subsubcourse_id' => $this->subsubcourse_id,
            'score' => $this->score,
        ]);

        return $dataProvider;
    }
}
