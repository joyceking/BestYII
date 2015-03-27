<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MRecommend */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mrecommend',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mrecommends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->recommend_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mrecommend-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
