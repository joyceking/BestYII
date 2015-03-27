<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MGroupStudent;

/**
 * MGroupStudentSearch represents the model behind the search form about `common\models\MGroupStudent`.
 */
class MGroupStudentSearch extends MGroupStudent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_student_id', 'group_id', 'student_id'], 'integer'],
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
        $query = MGroupStudent::find()->where(['group_id'=>$params['group_id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'group_student_id' => $this->group_student_id,
            'group_id' => $this->group_id,
            'student_id' => $this->student_id,
        ]);

        return $dataProvider;
    }
}
