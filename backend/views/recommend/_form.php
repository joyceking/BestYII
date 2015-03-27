<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\MRecommend;

/* @var $this yii\web\View */
/* @var $model common\models\MRecommend */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mrecommend-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 16]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 64]) ?>

	<?= $form->field($model, 'status')->dropDownList(MRecommend::getRecommendStatusOptionName()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
