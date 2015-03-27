<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\MTeacher;
use common\models\MSchool;
use common\models\MGroup;

?>

<div class="mcourse-schedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'teacher_id')->dropDownList(MTeacher::getDropDownList(MSchool::getSchoolIdFromSession())) ?>

    <?= $form->field($model, 'start_time')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', ['phpDatetimeFormat'=>'yyyy-MM-dd HH:mm:ss']) ?>

    <?= $form->field($model, 'is_repay')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
    <?= $form->field($model, 'teacher_id')->textInput(['maxlength' => 10]) ?>
    <?= $form->field($model, 'room_id')->textInput(['maxlength' => 10]) ?>


    <?= $form->field($model, 'course_unit_id')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'room_id')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'start_time')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', ['phpDatetimeFormat'=>'yyyy-MM-dd']) ?>

    <?= $form->field($model, 'start_time')->textInput() ?>


*/