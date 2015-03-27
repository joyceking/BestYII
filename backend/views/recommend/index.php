<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\MRecommend;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MRecommendSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Mrecommends');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mrecommend-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Mrecommend',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'recommend_id',
            //'openid',
            [
                'label' => '推荐人',
                'value'=>function ($model, $key, $index, $column) { return (empty($model->refereesName->nickname) ? '' : $model->refereesName->nickname); },
                'filter'=> true,
            ],

            //'gh_id',
            //'openid',
            'name',
            'mobile',
            [
                'label' => '状态',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) {
						return MRecommend::getRecommendStatusOptionName($model->status);
                },
            ],

            // 'create_time',

            //['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
