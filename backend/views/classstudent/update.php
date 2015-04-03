<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MGroupStudent */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mgroup Student',
]) . ' ' . $model->group_student_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mgroup Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->group_student_id, 'url' => ['view', 'id' => $model->group_student_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mgroup-student-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
