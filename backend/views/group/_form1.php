<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\MTeacher;
use common\models\MSchool;
use common\models\MGroup;
?>

<div class="mgroup-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => 256, 'readonly'=>true]) ?>

    

	<?= $form->field($model, 'des')->label('课程')->textInput(['maxlength' => 256, 'value'=>$model->course->title, 'readonly'=>true]) ?>    

	<?= $form->field($model, 'des')->label('课时')->textInput(['maxlength' => 256, 'value'=>$model->courseUnit->title, 'readonly'=>true]) ?>    

    <?= $form->field($model, 'teacher_id')->dropDownList(MTeacher::getDropDownList(MSchool::getSchoolIdFromSession()))->label('授课老师') ?>

    <?= $form->field($model, 'startTime')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', ['phpDatetimeFormat'=>'yyyy-MM-dd HH:mm:ss']) ?>

	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
	<?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'course_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\MCourse::find()->where(['school_id'=>\common\models\MSchool::getSchoolIdFromSession()])->all(),
        'course_id',
        'title'
    ), []) 
	?>


*/
