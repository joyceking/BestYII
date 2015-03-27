<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MSchoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'School');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mschool-index">
    <!--
    <h1><?//= Html::encode($this->title) ?></h1>
    -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//		'tableOptions' => ['class' => 'table table-striped'],        
        'columns' => [
           ////['class' => 'yii\grid\SerialColumn'],
			[
				'label' => 'Logo',
				'attribute' => 'logo_url',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
						//return Html::img($model->logo_url, ['width'=>'64']);
						return Html::img($model->getLogoUrl(), ['width'=>'64']);
				},
				'filter'=> false,
			],

            'school_id',
/*
			[
				'label' => '公众号',
				'attribute'=>'gh.gh_id',
			],
*/
            'title',
            'slogan',
          //'logo_url:url',

//            'des:ntext',
            // 'addr',
            // 'mobile',
            // 'website',
            // 'create_time',
            // 'update_time',
            // 'is_delete',
			[
				'label' => '校区数量',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return count($model->schoolBranches).' '.Html::a('<span>详情</span>', ['schoolbranch/index', 'school_id'=>$model->school_id], [
						'title' => '校区',
					]);
				},
			],

			[
				'label' => '学校相册',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return $model->getPhotosCount().' '.Html::a('<span>详情</span>', ['myphotos/index', 'owner_cat'=>$model->ownerCat, 'owner_id'=>$model->getPrimaryKey()]);
				},
			],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>

