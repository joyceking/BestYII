<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MCourseScheduleSignon */

/*
$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mcourse Schedule Signon',
]) . ' ' . $model->signon_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mcourse Schedule Signons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->signon_id, 'url' => ['view', 'id' => $model->signon_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
*/

$this->title = Yii::t('backend', 'Course Schedule Signon');
$this->params['breadcrumbs'][] =  ['label'=>"{$courseSchedule->group->title} [{$courseSchedule->group->group_id}]", 'url'=>['/group']];
$this->params['breadcrumbs'][] =  ['label'=>"{$courseSchedule->courseUnitInfo}", 'url'=>['/courseschedule', 'group_id'=>$courseSchedule->group->group_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');

?>

<div class="mcourse-schedule-signon-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
