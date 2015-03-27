<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MSchool;

/**
 * MSchoolSearch represents the model behind the search form about `common\models\MSchool`.
 */
class MSchoolSearch extends MSchool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'is_delete'], 'integer'],
            [['title', 'slogan', 'logo_url', 'des', 'addr', 'mobile', 'website', 'create_time', 'update_time'], 'safe'],
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
        $query = MSchool::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'school_id' => $this->school_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'is_delete' => $this->is_delete,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slogan', $this->slogan])
            ->andFilterWhere(['like', 'logo_url', $this->logo_url])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'addr', $this->addr])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'website', $this->website]);

        return $dataProvider;
    }
}
