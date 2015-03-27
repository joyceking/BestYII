<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MRoom */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mroom',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mrooms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->room_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mroom-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
