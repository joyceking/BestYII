<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Teacher'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mteacher-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
