<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MCourse */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mcourses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcourse-view">

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->course_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->course_id], [
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
            'course_id',
            'title',
            'des:ntext',
            //'is_delete',
        ],
    ]) ?>

</div>
