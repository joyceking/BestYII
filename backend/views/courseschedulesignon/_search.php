<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MCourseScheduleSignonSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcourse-schedule-signon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'signon_id') ?>

    <?= $form->field($model, 'course_schedule_id') ?>

    <?= $form->field($model, 'student_id') ?>

    <?= $form->field($model, 'is_signon') ?>

    <?= $form->field($model, 'is_repay') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
