<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

use common\models\MPhotoOwner;

$this->title = Yii::t('backend', 'Photo Owner');
$this->params['breadcrumbs'][] =  ['label'=>$photo->title, 'url'=>['/photo']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mphoto-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a(Yii::t('backend', 'Create'), ['create', 'photo_id'=>$photo->photo_id], ['class' => 'btn btn-success']) 
			echo Html::a(Html::img(Url::to($photo->getPicUrl()), ['width'=>'75']), $photo->getPicUrl());
		?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//		'tableOptions' => ['class' => 'table table-striped'],        
        'columns' => [
//            'photo_owner_id',
			[
				'attribute' => 'photo_id',
				'filter'=> false,				
			],

               [
                    'attribute' => 'owner_cat',
                    'value'=>function ($model, $key, $index, $column) { return MPhotoOwner::getPhotoOwnerCatOptionName($model->owner_cat); },
                    'filter'=>  MPhotoOwner::getPhotoOwnerCatOptionName(),
               ],


            'owner_id',
            'sort_order',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>


</div>

	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>

<?php
/*
	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>

*/