<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mgroup-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>
    <?=
    $form->field($model, 'course_unit_id')->dropDownList(\yii\helpers\ArrayHelper::map(
                    \common\models\MCourse::getAllSubcourses(), 'subcourse_id', 'title'
            ), [])
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
	<?= $form->field($model, 'status')->textInput() ?>
*/
