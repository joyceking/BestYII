<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use common\models\MPhotoOwner;
?>

<div class="mphoto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'photo_id')->hiddenInput(['maxlength' => 10])->label(false) ?>

    <div class="row">
        <div class="col-sm-4">
			<?= $form->field($model, 'owner_cat')->dropDownList(MPhotoOwner::getPhotoOwnerCatOptionName()) ?> 
        </div>
        <div class="col-sm-4">
		    <?= $form->field($model, 'owner_id')->textInput(['maxlength' => 10]) ?>
        </div>
        <div class="col-sm-4">
		    <?= $form->field($model, 'sort_order')->textInput(['maxlength' => 10]) ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create Photo Owner') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
    <?= $form->field($model, 'photo_id')->textInput(['maxlength' => 10]) ?>


*/