<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MTeacherSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mteacher-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'teacher_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'sex') ?>

    <?= $form->field($model, 'birthday') ?>

    <?= $form->field($model, 'nationality') ?>

    <?php // echo $form->field($model, 'identify_id') ?>

    <?php // echo $form->field($model, 'onboard_time') ?>

    <?php // echo $form->field($model, 'des') ?>

    <?php // echo $form->field($model, 'sort_order') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
