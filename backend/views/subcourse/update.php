<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MSubcourse */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] =  ['label'=>"课程".'['.$model->course->title.']', 'url'=>['/course']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Msubcourses'), 'url' => ['index', 'course_id'=>$model->course->course_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="msubcourse-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
