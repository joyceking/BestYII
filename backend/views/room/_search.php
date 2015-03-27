<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MRoomSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mroom-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'room_id') ?>

    <?= $form->field($model, 'school_branch_id') ?>

    <?= $form->field($model, 'title') ?>

    <!--
    <?//= $form->field($model, 'is_delete') ?>
    -->

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
