<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MSchoolBranch;

/**
 * MSchoolBranchSearch represents the model behind the search form about `common\models\MSchoolBranch`.
 */
class MSchoolBranchSearch extends MSchoolBranch
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_branch_id', 'school_id', 'is_delete'], 'integer'],
            [['title', 'des', 'addr', 'mobile', 'create_time', 'update_time'], 'safe'],
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
        $query = MSchoolBranch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'school_branch_id' => $this->school_branch_id,
            'school_id' => $this->school_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'addr', $this->addr])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);

        return $dataProvider;
    }
}
