<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MPhoto;

/**
 * MPhotoSearch represents the model behind the search form about `common\models\MPhoto`.
 */
class MPhotoSearch extends MPhoto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photo_id', 'owner_cat', 'owner_id', 'sort_order'], 'integer'],
            [['title', 'pic_url', 'create_time'], 'safe'],
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
        $query = MPhoto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'photo_id' => $this->photo_id,
            'owner_cat' => $this->owner_cat,
            'owner_id' => $this->owner_id,
            'sort_order' => $this->sort_order,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'pic_url', $this->pic_url]);

        return $dataProvider;
    }
}
