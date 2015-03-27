<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MParentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mparent-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'student_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'sex') ?>

    <?= $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'addr') ?>

    <?php // echo $form->field($model, 'qq') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
