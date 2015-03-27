<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MParent;

/**
 * MParentSearch represents the model behind the search form about `common\models\MParent`.
 */
class MParentSearch extends MParent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'student_id', 'is_delete'], 'integer'],
            [['name', 'sex', 'mobile', 'addr', 'qq', 'email'], 'safe'],
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
        $query = MParent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'parent_id' => $this->parent_id,
            'student_id' => $this->student_id,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'addr', $this->addr])
            ->andFilterWhere(['like', 'qq', $this->qq])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
