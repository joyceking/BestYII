<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Course');
$this->params['breadcrumbs'][] = $this->title;
?>

<!--
<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Panel title</h3></div><div class="panel-body">
-->

<div class="mcourse-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
		'tableOptions' => ['class' => 'table table-striped'],        
		'layout' => "<div class='panel panel-default'><div class='panel-heading'><h3 class='panel-title'>{summary}</h3></div><div class='panel-body'>\n{items}\n{pager}</div></div>",
        'columns' => [
            'course_id',
            'title',
            'type',
            //'des:ntext',
            'object:ntext',
//            'feature:ntext',
//            'target_student:ntext',
//            'website:ntext',
			[
				'label' => '多少课时',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return count($model->courseUnits).' '.Html::a('<span>详情</span>', ['courseunit/index', 'course_id'=>$model->course_id]);
				},
			],
			[
				'label' => '设计了多少子课程',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return count($model->subcourses).' '.Html::a('<span>详情</span>', ['subcourse/index', 'course_id'=>$model->course_id]);
				},
			],

			[
				'label' => '课时已制定完毕',
				'value'=>function ($model, $key, $index, $column) { 
					return $model->isAvailable() ? '是' : '否';
				},
			],

			[
				'label' => '课程相册',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return $model->getPhotosCount().' '.Html::a('<span>详情</span>', ['myphotos/index', 'owner_cat'=>$model->ownerCat, 'owner_id'=>$model->getPrimaryKey()]);
				},
			],

			['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>


<!--  
  </div>

</div>
-->