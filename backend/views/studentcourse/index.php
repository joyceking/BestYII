<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('backend', 'Course');
$this->params['breadcrumbs'][] =  ['label'=>"{$student->name} [{$student->student_id}]", 'url'=>['/student']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mstudent-course-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a(Yii::t('backend', 'Create'), ['create', 'student_id'=>$student->student_id], ['class' => 'btn btn-success']) ?>
    </p>


	<?php yii\widgets\Pjax::begin(['id' => 'id_student_course_gridview']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
/*
            [
                'attribute' => 'student_id',
                'headerOptions' => array('style'=>'width:80px;'),           
            ],
*/
            'course_id', 

			[
                'label' => '课程名称',
                //'attribute' => 'course.title',
                'value'=>function ($model, $key, $index, $column) { return (empty($model->course->title) ? '' : $model->course->title). ' ['. count($model->course->courseUnits)."课时" . ']'; },
                'filter'=> true,
            ],

            'score',

               [
                    'attribute' => '状态',
                    'value'=>function ($model, $key, $index, $column) { return common\models\MStudentCourse::getStudentCourseStatusOptionName($model->status); },
                    'filter'=> common\models\MStudentCourse::getStudentCourseStatusOptionName(),
               ],
/*
            [
                'label' => '课时计划',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) {
                    return Html::a('<span>安排课时计划</span>', ['courseschedule/index', 'student_id'=>$model->student_id, 'course_id'=>$model->course_id]);
                },
            ],
*/
             ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

	<?php yii\widgets\Pjax::end() ?>

	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div>
