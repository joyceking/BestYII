<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Create');
$this->params['breadcrumbs'][] =  ['label'=>$student->name, 'url'=>['/student']];
$this->params['breadcrumbs'][] =  ['label'=> Yii::t('backend', 'Course'), 'url'=>['/studentcourse', 'student_id'=>$student->student_id]];
$this->params['breadcrumbs'][] =  $this->title;

?>
<div class="mstudent-course-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
