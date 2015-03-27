<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MSchoolBranch */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Mschool Branch',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mschool Branches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mschool-branch-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
