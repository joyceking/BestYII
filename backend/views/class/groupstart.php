<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MGroup */

$this->title = Yii::t('backend', 'Group Start');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
$this->params['breadcrumbs'][] = Yii::t('backend', 'Group Start');
?>
<div class="mgroup-update">

    <?= $this->render('_form1', [
        'model' => $model,
    ]) ?>

</div>
