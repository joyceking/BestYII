<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MSchoolBranch */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Mschool Branch',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mschool Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->school_branch_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="mschool-branch-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
