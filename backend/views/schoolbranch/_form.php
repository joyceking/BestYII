<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MSchoolBranch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mschool-branch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'region')->dropDownList(\common\models\MSchool::getRegionOptionName()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'des')->widget(
        \yii\imperavi\Widget::className(),
        [
            'plugins' => ['fullscreen','fontcolor'],
            'options'=>[
                'minHeight'=>200,
                'maxHeight'=>400,
                'buttonSource'=>true,
                'convertDivs'=>false,
                'removeEmptyTags'=>false,
                'imageUpload'=>Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
            ]
        ]
    ) ?>

    <?= $form->field($model, 'addr')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'lon')->textInput(['maxlength' => 24]) ?>

    <?= $form->field($model, 'lat')->textInput(['maxlength' => 24]) ?>

    <!--
    <?//= $form->field($model, 'create_time')->textInput() ?>

    <?//= $form->field($model, 'update_time')->textInput() ?>

    <?//= $form->field($model, 'is_delete')->textInput() ?>
    -->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
