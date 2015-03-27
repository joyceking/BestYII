<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MCourseUnitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcourse-unit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'course_unit_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'des') ?>

    <?= $form->field($model, 'prepare') ?>

    <?php // echo $form->field($model, 'caution') ?>

    <?php // echo $form->field($model, 'minutes') ?>

    <?php // echo $form->field($model, 'sort_order') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
