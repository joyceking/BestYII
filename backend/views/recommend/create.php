<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MRecommend */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Mrecommend',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mrecommends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mrecommend-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
