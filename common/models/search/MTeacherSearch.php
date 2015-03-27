<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MTeacher;
use common\models\MSchool;

/**
 * MTeacherSearch represents the model behind the search form about `common\models\MTeacher`.
 */
class MTeacherSearch extends MTeacher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'sort_order'], 'integer'],
            [['name', 'sex', 'birthday', 'nationality', 'identify_id', 'onboard_time', 'des', 'create_time'], 'safe'],
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
        $query = MTeacher::find()->where(['school_id'=>MSchool::getSchoolIdFromSession()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'teacher_id' => $this->teacher_id,
            'birthday' => $this->birthday,
            'onboard_time' => $this->onboard_time,
            'sort_order' => $this->sort_order,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'nationality', $this->nationality])
            ->andFilterWhere(['like', 'identify_id', $this->identify_id])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
