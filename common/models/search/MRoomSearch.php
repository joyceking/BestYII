<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MRoom;

/**
 * MRoomSearch represents the model behind the search form about `common\models\MRoom`.
 */
class MRoomSearch extends MRoom
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id', 'school_branch_id', 'is_delete'], 'integer'],
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
        $query = MRoom::find()->where(['school_branch_id' => $params['school_branch_id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'room_id' => $this->room_id,
            'school_branch_id' => $this->school_branch_id,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
