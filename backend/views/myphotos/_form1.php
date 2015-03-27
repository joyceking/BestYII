<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use common\models\MPhotoOwner;
?>

<div class="mphoto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($photo, 'title')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($photo, 'des')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($photo, 'tags')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'sort_order')->textInput(['maxlength' => 32]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Upload') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
