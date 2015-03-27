<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MCourseScheduleSignon */

$this->title = Yii::t('backend', 'Create', [
    'modelClass' => 'Mcourse Schedule Signon',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mcourse Schedule Signons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcourse-schedule-signon-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
