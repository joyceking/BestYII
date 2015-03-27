<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MSchool */

$this->title = Yii::t('backend', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'School'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mschool-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
