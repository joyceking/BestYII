<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="mparent-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'student_id')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 16]) ?>

	<?= $form->field($model, 'sex')->dropDownList(\common\models\MSchool::getSexOptionName()) ?>	

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'addr')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'qq')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 64]) ?>
    <!--
    <?//= $form->field($model, 'is_delete')->textInput() ?>
    -->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
