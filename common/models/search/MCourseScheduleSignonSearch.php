<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MCourseScheduleSignon;

/**
 * MCourseScheduleSignonSearch represents the model behind the search form about `common\models\MCourseScheduleSignon`.
 */
class MCourseScheduleSignonSearch extends MCourseScheduleSignon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['signon_id', 'course_schedule_id', 'student_id', 'is_signon', 'is_repay'], 'integer'],
            [['create_time'], 'safe'],
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
		$query = MCourseScheduleSignon::find()->where(['course_schedule_id'=>$params['course_schedule_id']])->orderBy(['signon_id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'signon_id' => $this->signon_id,
            'course_schedule_id' => $this->course_schedule_id,
            'student_id' => $this->student_id,
            'is_signon' => $this->is_signon,
            'is_repay' => $this->is_repay,
            'create_time' => $this->create_time,
        ]);

        return $dataProvider;
    }
}
