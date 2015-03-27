<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MKeyword */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Settings'), 'url' => ['index']];
?>
<div class="mkeyword-update">

    <?= $this->render('_form', [
        'model' => $model,
        'rich' => $rich,
    ]) ?>

</div>
