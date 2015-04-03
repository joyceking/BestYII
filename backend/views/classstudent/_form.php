<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MGroupStudent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mgroup-student-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'student_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        $group->getFreeStudents(),
        'student_id',
        'name'
    ), ['prompt'=>'']) ?>


	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Join this Group') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
    <?= $form->field($model, 'group_student_id')->textInput(['maxlength' => 10]) ?>
   
	<?= $form->field($model, 'group_id')->textInput(['maxlength' => 10]) ?>
*/