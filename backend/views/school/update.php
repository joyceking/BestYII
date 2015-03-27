<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MSchool */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'School'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->school_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mschool-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
