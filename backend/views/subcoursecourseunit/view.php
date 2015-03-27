<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MSubcourseCourseUnit */

$this->title = $model->subcourse_course_unit_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Msubcourse Courseunits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="msubcourse-courseunit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->subcourse_course_unit_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->subcourse_course_unit_id], [
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
            'subcourse_course_unit_id',
            'subcourse_id',
            'course_unit_id',
        ],
    ]) ?>

</div>
