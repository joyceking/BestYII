<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

?>

<?php
  $this->registerJs(
	 '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
	 yii\web\View::POS_READY
  );
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-success flash-success">
	   <?php echo Yii::$app->session->getFlash('success'); ?>
  </div>
<?php endif; ?>

<div class="mphoto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'owner_id')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'owner_name')->textInput(['maxlength' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Bind') : Yii::t('backend', 'Update Bind'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



