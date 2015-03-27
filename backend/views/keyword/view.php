<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MKeyword */

$this->title = $model->keyword_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Mkeywords'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mkeyword-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->keyword_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->keyword_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'keyword_id',
            'keyword',
            'value:ntext',
            'create_time',
            'update_time',
        ],
    ]) ?>

</div>
