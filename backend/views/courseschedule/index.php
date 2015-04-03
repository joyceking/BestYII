<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = Yii::t('backend', 'Course Schedule');
//$this->params['breadcrumbs'][] =  ['label'=>"{$group->title} [{$group->group_id}]", 'url'=>['/group']];

$this->params['breadcrumbs'][] =  ['label'=>$group->courseUnit->course->title, 'url'=>['/course']];
$this->params['breadcrumbs'][] =  ['label'=>"{$group->title} [{$group->group_id}]", 'url'=>['/class']];

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mcourse-schedule-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'course_schedule_id',
			[
				'attribute' => 'course_unit_id',
                //'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return $model->courseUnit->getCourseUnitFullInfo();
				},
			],
            'teacher_id',
			[
				'label'=>'教师姓名',
				'attribute' => 'teacher.name',
			],

//            'room_id',
            'start_time',
			[
				'label' => '学生签到情况',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return Html::a('<span>详情</span>', ['courseschedulesignon/index', 'course_schedule_id'=>$model->course_schedule_id]);
				},
			],

            // 'is_repay',
//            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],
        ],
    ]); ?>

</div>
