<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MGroupStudent */

$this->title = $model->group_student_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mgroup Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mgroup-student-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->group_student_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->group_student_id], [
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
            'group_student_id',
            'group_id',
            'student_id',
        ],
    ]) ?>

</div>
