<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MSubcourseCourseUnit */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Msubcourse Courseunit',
]) . ' ' . $model->subcourse_course_unit_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Msubcourse Courseunits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subcourse_course_unit_id, 'url' => ['view', 'id' => $model->subcourse_course_unit_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="msubcourse-courseunit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
