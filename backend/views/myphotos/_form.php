<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use common\models\MPhotoOwner;
?>

<div class="mphoto-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-sm-4">
		    <?= $form->field($model, 'title')->textInput(['maxlength' => 64]) ?>
        </div>
        <div class="col-sm-4">
		    <?= $form->field($model, 'des')->textInput(['maxlength' => 128]) ?>
        </div>
        <div class="col-sm-4">
		    <?= $form->field($model, 'tags')->textInput(['maxlength' => 128]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-6">
		    <?= $form->field($model, 'pic_url')->fileInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Upload Photo') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>




<?php
/*


*/