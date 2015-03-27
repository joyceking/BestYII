<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MKeyword */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Mkeyword',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mkeywords'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mkeyword-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
