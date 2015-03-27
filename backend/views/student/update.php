<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MStudent */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Student'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->student_id]];

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');

?>
<div class="mstudent-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
