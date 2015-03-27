<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MStudent;
use common\models\MSchool;

/**
 * MStudentSearch represents the model behind the search form about `common\models\MStudent`.
 */
class MStudentSearch extends MStudent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'school_branch_id', 'is_delete'], 'integer'],
            [['name', 'sex', 'birthday', 'nationality', 'create_time'], 'safe'],
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
        $query = MStudent::find()->where(['school_id'=>MSchool::getSchoolIdFromSession()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'student_id' => $this->student_id,
            'school_id' => $this->school_id,
            'school_branch_id' => $this->school_branch_id,
            'birthday' => $this->birthday,
            'create_time' => $this->create_time,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'nationality', $this->nationality]);

        return $dataProvider;
    }
}
