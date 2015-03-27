<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MCourseScheduleSignon */

$this->title = $model->signon_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mcourse Schedule Signons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcourse-schedule-signon-view">

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->signon_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->signon_id], [
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
            'signon_id',
            'course_schedule_id',
            'student_id',
            'is_signon',
            'is_repay',
            'create_time',
        ],
    ]) ?>

</div>
