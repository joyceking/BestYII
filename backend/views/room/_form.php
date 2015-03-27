<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MRoom */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mroom-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>

	<!--
    <?//= $form->field($model, 'school_branch_id')->textInput(['maxlength' => 10]) ?>
    <?//= $form->field($model, 'is_delete')->textInput() ?>
    -->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
