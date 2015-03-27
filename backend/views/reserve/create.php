<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MReserve */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Mreserve',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mreserves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mreserve-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
