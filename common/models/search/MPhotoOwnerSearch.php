<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MPhotoOwner;

class MPhotoOwnerSearch extends MPhotoOwner
{
    public function rules()
    {
        return [
            [['photo_owner_id', 'photo_id', 'owner_cat', 'sort_order'], 'integer'],
            [['owner_id'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MPhotoOwner::find()->where(['photo_id'=>$params['photo_id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'photo_owner_id' => $this->photo_owner_id,
            'photo_id' => $this->photo_id,
            'owner_cat' => $this->owner_cat,
            'owner_id' => $this->owner_id,
            'sort_order' => $this->sort_order,
        ]);

        return $dataProvider;
    }
}
