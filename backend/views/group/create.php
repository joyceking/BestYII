<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MGroup */

$this->title = Yii::t('backend', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mgroup-create">

    <?= $this->render('_form', [
        'model' => $model,
        'courseUnit'=>$courseUnit,
    ]) ?>

</div>
