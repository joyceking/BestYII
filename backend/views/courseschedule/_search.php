<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MCourseScheduleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcourse-schedule-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'course_schedule_id') ?>

    <?= $form->field($model, 'teacher_id') ?>

    <?= $form->field($model, 'course_unit_id') ?>

    <?= $form->field($model, 'room_id') ?>

    <?= $form->field($model, 'start_time') ?>

    <?php // echo $form->field($model, 'is_repay') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
