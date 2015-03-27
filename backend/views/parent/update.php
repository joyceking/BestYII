<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MParent */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mparent',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mparents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->parent_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mparent-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
