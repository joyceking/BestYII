<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MCourseSchedule */

$this->title = $model->course_schedule_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mcourse Schedules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcourse-schedule-view">

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->course_schedule_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->course_schedule_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'course_schedule_id',
            'teacher_id',
            'course_unit_id',
            'room_id',
            'start_time',
            'is_repay',
        ],
    ]) ?>

</div>
