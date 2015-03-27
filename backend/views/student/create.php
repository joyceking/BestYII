<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Create');
/*
$this->params['breadcrumbs'][] =  ['label'=>$schoolBranch->school->title, 'url'=>['/school']];
$this->params['breadcrumbs'][] =  ['label'=>$schoolBranch->title, 'url'=>['/schoolbranch', 'school_id'=>$schoolBranch->school->school_id]];
$this->params['breadcrumbs'][] =  ['label'=>Yii::t('backend', 'Student'), 'url'=>['/student', 'school_branch_id'=>$schoolBranch->school_branch_id]];
*/
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Student'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mstudent-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
