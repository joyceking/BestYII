<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\MCourse;
use common\models\MReserve;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MReserveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Mreserves');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mreserve-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Mreserve',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'reserve_id',

            //'school_branch_id',
            [
                'label' => '校区',
                //'attribute' => 'course.title',
                'value'=>function ($model, $key, $index, $column) { return (empty($model->schoolBranch->title) ? '' : $model->schoolBranch->title); },
                'filter'=> true,
            ],

            //'course_id',
            [
                'label' => '课程名称',
                //'attribute' => 'course.title',
                'value'=>function ($model, $key, $index, $column) { return (empty($model->course->title) ? '' : $model->course->title); },
                'filter'=> true,
            ],
            'name',
            //'sex',
            [
                'attribute' => 'sex',
                'value'=>function ($model, $key, $index, $column) { return MReserve::getSexOptionName($model->sex); },
                'filter'=> MReserve::getSexOptionName(),
            ],            
            'age',
            'mobile',
            [
                'label' => '状态',
                'value'=>function ($model, $key, $index, $column) {
                    return MReserve::getReserveStatusOptionName($model->status);
                },
            ],
            'memo',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
