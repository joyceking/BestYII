<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MCourseScheduleSignonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Course Schedule Signon');
//$this->params['breadcrumbs'][] =  ['label'=>"{$courseSchedule->group->title} [{$courseSchedule->group->group_id}]", 'url'=>['/group']];

$this->params['breadcrumbs'][] =  ['label'=>$courseSchedule->group->courseUnit->course->title, 'url'=>['/course']];
$this->params['breadcrumbs'][] =  ['label'=>$courseSchedule->group->courseUnit->title, 'url'=>['/courseunit', 'course_id'=>$courseSchedule->group->courseUnit->course_id]];
$this->params['breadcrumbs'][] =  ['label'=>"{$courseSchedule->group->title} [{$courseSchedule->group->group_id}]", 'url'=>['/group', 'course_unit_id'=>$courseSchedule->group->courseUnit->course_unit_id]];
$this->params['breadcrumbs'][] =  ['label'=>"课程计划", 'url'=>['/courseschedule', 'group_id'=>$courseSchedule->group->group_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mcourse-schedule-signon-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'signon_id',
            'course_schedule_id',
            'student_id',
            'student.name',
			[
                'label' => '签到状态',
                'attribute' => 'is_signon',
                'value'=>function ($model, $key, $index, $column) { return $model->getSignonStatusName(); },
            ],
			[
                'attribute' => 'is_repay',
                'value'=>function ($model, $key, $index, $column) { return $model->getRepayStatusName(); },
            ],

            //'score',
            'memo',
            // 'create_time',

//            ['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],

        ],
    ]); ?>

</div>
