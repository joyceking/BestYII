<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MSchoolBranchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', '校区');
$this->params['breadcrumbs'][] =  ['label'=>$school->title, 'url'=>['/school']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mschool-branch-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create', 'school_id'=>$school->school_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'school_branch_id',
			[
				'attribute' => 'school_id',
				'headerOptions' => array('style'=>'width:80px;'),	
				'filter'=> false,				
				'visible' => false,
			],
            'region',
            'title',
            'des:ntext',
            'addr',
            // 'mobile',
            // 'create_time',
            // 'update_time',
            // 'is_delete',
            'lon',
            'lat',

/*
			[
				'label' => '学生数量',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return count($model->students).' '.Html::a('<span>详情</span>', ['student/index', 'school_branch_id'=>$model->school_branch_id], [
						'title' => '学生',
					]);
				},
			],
*/
			[
				'label' => '教室数量',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return count($model->rooms).' '.Html::a('<span>详情</span>', ['room/index', 'school_branch_id'=>$model->school_branch_id], [
						'title' => '教室',
					]);
				},
			],
			[
				'label' => '校区相册',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return $model->getPhotosCount().' '.Html::a('<span>详情</span>', ['myphotos/index', 'owner_cat'=>$model->ownerCat, 'owner_id'=>$model->getPrimaryKey()]);
				},
			],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
