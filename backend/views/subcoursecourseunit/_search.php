<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MSubcourseCourseUnitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="msubcourse-courseunit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'subcourse_course_unit_id') ?>

    <?= $form->field($model, 'subcourse_id') ?>

    <?= $form->field($model, 'course_unit_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
