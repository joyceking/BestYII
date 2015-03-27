<?php

use yii\helpers\Html;


$this->title = Yii::t('backend', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Course'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcourse-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
