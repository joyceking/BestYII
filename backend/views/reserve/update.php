<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MReserve */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mreserve',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mreserves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->reserve_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mreserve-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
