<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MRoom */

$this->title = Yii::t('backend', 'Create', ['modelClass' => 'Mroom']);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mrooms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mroom-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
