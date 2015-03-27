<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MCourseSchedule */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mcourse Schedule',
]) . ' ' . $model->course_schedule_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mcourse Schedules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->course_schedule_id, 'url' => ['view', 'id' => $model->course_schedule_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mcourse-schedule-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
