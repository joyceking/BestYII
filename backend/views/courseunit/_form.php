<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\MSchool;
use common\models\MCourse;

/* @var $this yii\web\View */
/* @var $model common\models\MCourseUnit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcourse-unit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>

	<?= $form->field($model, 'des')->textarea(['rows' => 4]) ?>

	<?= $form->field($model, 'prepare')->textarea(['rows' => 4]) ?>
	
	<?= $form->field($model, 'caution')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'minutes')->textInput(['maxlength' => 10]) ?>
		
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
    <?= $form->field($model, 'sort_order')->textInput(['maxlength' => 10]) ?>


    <?= $form->field($model, 'course_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        MCourse::find()->where(['school_id'=>MSchool::getSchoolIdFromSession()])->all(),
        'course_id',
        'title'
    ), ['prompt'=>'']) ?>


*/
