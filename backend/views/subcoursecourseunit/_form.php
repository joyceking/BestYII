<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MSubcourseCourseUnit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="msubcourse-courseunit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_unit_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        $subcourse->course->courseUnits,
        'course_unit_id',
        'title'
    ), ['prompt'=>''])->label(Yii::t('backend','Course Unit ID')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Append Course Unit') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
