<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MSubcourseCourseUnit */

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Msubcourse Courseunit',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Msubcourse Courseunits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="msubcourse-courseunit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
