<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\MStudentSubcourse;
use common\models\MCourse;

/* @var $this yii\web\View */
/* @var $model common\models\MStudentSubcourse */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
 
$this->registerJs(
   '$("document").ready(function(){ 
        $("#id_student_course_form").on("pjax:end", function() {
            $.pjax.reload({container:"#id_student_course_gridview"});
        });
    });'
);
?>

<div class="mstudent-course-form">

	<?php yii\widgets\Pjax::begin(['id' => 'id_student_course_form']) ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subcourse_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        MCourse::getAllSubcourses(),
        'subcourse_id',
        'title'
    ), ['prompt'=>''])->label(Yii::t('backend','Subcourse')) ?>

	<?php if (!$model->isNewRecord): ?>
    <?= $form->field($model, 'score')->textInput(['maxlength' => 10]) ?>
	<?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Apply Sub Course') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	<?php yii\widgets\Pjax::end() ?>
</div>

<?php

/*
    <?= $form->field($model, 'course_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\MCourse::find()->where(['school_id'=>\common\models\MSchool::getSchoolIdFromSession()])->all(),
        'course_id',
        'title'
    ), ['prompt'=>''])->label(Yii::t('backend','Course')) ?>

AllSubcourses
*/