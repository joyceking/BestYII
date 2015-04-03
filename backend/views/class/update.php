<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MGroup */

$this->title = Yii::t('backend', 'Update') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->group_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mgroup-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
