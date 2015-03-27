<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\MReserve;

/* @var $this yii\web\View */
/* @var $model common\models\MReserve */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mreserve-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'school_branch_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\MSchoolBranch::find()->where(['school_id'=>\common\models\MSchool::getSchoolIdFromSession()])->all(),
        'school_branch_id',
        'title'
    ), []) ?>

    <?= $form->field($model, 'course_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\MCourse::find()->where(['school_id'=>\common\models\MSchool::getSchoolIdFromSession()])->all(),
        'course_id',
        'title'
    ), []) ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => 16]) ?>

    <?= $form->field($model, 'sex')->dropDownList(MReserve::getSexOptionName()) ?>   

    <?= $form->field($model, 'age')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'memo')->textInput(['maxlength' => 256]) ?>

    <!--
    <//?//= $form->field($model, 'status')->textInput(['maxlength' => 64]) ?>
    -->

    <?= $form->field($model, 'status')->dropDownList(MReserve::getReserveStatusOptionName()) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
