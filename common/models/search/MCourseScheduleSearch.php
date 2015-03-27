<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MCourseSchedule;

/**
 * MCourseScheduleSearch represents the model behind the search form about `common\models\MCourseSchedule`.
 */
class MCourseScheduleSearch extends MCourseSchedule
{
    public function rules()
    {
        return [
            [['course_schedule_id', 'teacher_id', 'course_unit_id', 'room_id', 'is_repay'], 'integer'],
            [['start_time'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MCourseSchedule::find()->where(['group_id'=>$params['group_id']])->orderBy(['course_schedule_id' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'course_schedule_id' => $this->course_schedule_id,
            'teacher_id' => $this->teacher_id,
            'course_unit_id' => $this->course_unit_id,
            'room_id' => $this->room_id,
            'start_time' => $this->start_time,
            'is_repay' => $this->is_repay,
        ]);

        return $dataProvider;
    }
}
