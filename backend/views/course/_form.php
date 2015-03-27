<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MCourse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcourse-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'type')->dropDownList(\common\models\MCourse::getCourseTypeOptionName()) ?>

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

    <?= $form->field($model, 'object')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'feature')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'target_student')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => 256]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
