<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\modules\wx\models\U;
use common\models\MPhoto;
use common\models\MPhotoOwner;

$this->title = Yii::t('backend', 'Photo');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mphoto-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//		'tableOptions' => ['class' => 'table table-striped'],        
        'columns' => [
		   [
			'label' => Yii::t('backend', 'Photo ID'),
			'attribute' => 'photo_id',
		   ],

		   [
			'label' => Yii::t('backend', 'Title'),
			'attribute' => 'title',
		   ],

		   [
			'label' => Yii::t('backend', 'Des'),
			'attribute' => 'des',
		   ],

		   [
			'label' => Yii::t('backend', 'Tags'),
			'attribute' => 'tags',
		   ],

		   [
			'label' => Yii::t('backend', 'Picture'),
			'format'=>'html',
				'value'=>function ($model, $key, $index, $column) {
					$photo = MPhoto::findOne($model['photo_id']);
					if ($photo->isVedio())
					{
						$url = Yii::$app->getRequest()->baseUrl.'/img/videoplay.png';
						return Html::a(Html::img(Url::to($url), ['width'=>'32']), $photo->getPicUrl());
					}
					else
						return Html::a(Html::img(Url::to($photo->getPicUrl()), ['width'=>'75']), $photo->getPicUrl());
				},
		   ],


		   [
			'label' => Yii::t('backend', 'Sort Order'),
			'attribute' => 'sort_order',
		   ],

		   [
			'label' => Yii::t('backend', 'Size'),
			'attribute' => 'size',
		   ],

//            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],

            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
//                    'template' => '{delete}',
                    'buttons' => [
                         'update' => function ($url, $model) {
                              return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['update', 'owner_cat'=>$_GET['owner_cat'], 'owner_id'=>$_GET['owner_id'], 'id'=>$model['photo_id']]), [
                                   'title' => Yii::t('yii', 'Update'),
                                   'data-pjax' => '0',
                              ]);
                         },
                         'delete' => function ($url, $model) {
                              return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['delete', 'owner_cat'=>$_GET['owner_cat'], 'owner_id'=>$_GET['owner_id'], 'id'=>$model['photo_id']]), [
                                   'title' => Yii::t('yii', 'Delete'),
				                   'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                   'data-method' => 'post',
                                   'data-pjax' => '0',
                                   //'data-pjax' => '1',
                              ]);
                         }
                    ],
               ],

        ],
    ]); ?>

</div>

	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>
