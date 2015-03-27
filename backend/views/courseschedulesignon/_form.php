<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\MCourseScheduleSignon;
use common\models\MSchool;

/* @var $this yii\web\View */
/* @var $model common\models\MCourseScheduleSignon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcourse-schedule-signon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_schedule_id')->textInput(['maxlength' => 10, 'readonly'=>true,]) ?>

    <?= $form->field($model, 'student_id')->textInput(['maxlength' => 10, 'readonly'=>true,]) ?>

	<?= $form->field($model, 'is_signon')->dropDownList(MCourseScheduleSignon::getSignonStatusOptionName()) ?>

    <?= $form->field($model, 'memo')->textarea(['rows' => 3]) ?>

	<?= $form->field($model, 'is_repay')->dropDownList(MSchool::getYesNoOptionName()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'is_signon')->textInput() ?>
    <?= $form->field($model, 'is_repay')->textInput() ?>


*/