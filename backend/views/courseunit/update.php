<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] =  ['label'=>$course->title, 'url'=>['/course']];
$this->params['breadcrumbs'][] =  ['label'=> Yii::t('backend', 'CourseUnit'), 'url'=>['/courseunit', 'course_id'=>$course->course_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mcourse-unit-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
