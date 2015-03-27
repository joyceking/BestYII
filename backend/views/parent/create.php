<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MParent */

$this->title = Yii::t('backend', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Parent'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mparent-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
