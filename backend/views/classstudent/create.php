<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MGroupStudent */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Mgroup Student',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mgroup Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mgroup-student-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
