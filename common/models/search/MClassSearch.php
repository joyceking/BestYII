<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MClass;

/**
 * MGroupSearch represents the model behind the search form about `common\models\MGroup`.
 */
class MClassSearch extends MClass
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'course_unit_id', 'status'], 'integer'],
            [['title', 'create_time'], 'safe'],
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
        $query = MClass::find();

        $query->andFilterWhere([
            'course_unit_id' => $this->course_unit_id,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'group_id' => $this->group_id,
            'course_unit_id' => $this->course_unit_id,
            'status' => $this->status,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
