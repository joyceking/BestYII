<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MCourseSchedule */

$this->title = Yii::t('backend', 'Create', [
    'modelClass' => 'Mcourse Schedule',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mcourse Schedules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcourse-schedule-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
