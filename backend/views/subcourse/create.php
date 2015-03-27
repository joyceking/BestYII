<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MSubcourse */

$this->title = Yii::t('backend', 'Create');
$this->params['breadcrumbs'][] =  ['label'=>"课程", 'url'=>['/course']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Msubcourses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="msubcourse-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
