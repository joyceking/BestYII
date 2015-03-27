<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MGroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mgroup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'group_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'course_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'create_time') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
