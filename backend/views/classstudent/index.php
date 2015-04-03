<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('backend', 'Group Student');
$this->params['breadcrumbs'][] = ['label' => "{$group->title} [{$group->group_id}]", 'url' => ['/class']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mstudent-course-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?php //echo Html::a(Yii::t('backend', 'Create'), ['create', 'student_id'=>$student->student_id], ['class' => 'btn btn-success'])  ?>
    </p>


    <?php yii\widgets\Pjax::begin(['id' => 'id_student_course_gridview']) ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'group_id',
            'student_id',
            [
                'label' => '学生姓名',
                'attribute' => 'student.name',
//                'value'=>function ($model, $key, $index, $column) { return (empty($model->course->title) ? '' : $model->course->title). ' ['. count($model->course->courseUnits)."课时" . ']'; },
//                'filter'=> true,
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]);
    ?>

    <?php yii\widgets\Pjax::end() ?>

    <?=
    $this->render('_form', [
        'model' => $model,
        'group' => $group,
    ])
    ?>


</div>