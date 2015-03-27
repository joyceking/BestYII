<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\MGroup;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Group');
$this->params['breadcrumbs'][] =  ['label'=>$courseUnit->course->title, 'url'=>['/course']];
$this->params['breadcrumbs'][] =  ['label'=>$courseUnit->title, 'url'=>['/courseunit', 'course_id'=>$courseUnit->course_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mgroup-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        
		'columns' => [
            
            'group_id',
            
			'title',
            
			[
                'label' => '课程',
                'value'=>function ($model, $key, $index, $column) { return (empty($model->courseUnit->course->title) ? '' : $model->courseUnit->course->getCourseFullInfo()); },
                'filter'=> true,
            ],
			[
                'label' => '课时',
                'value'=>function ($model, $key, $index, $column) { return (empty($model->courseUnit->title) ? '' : $model->courseUnit->title); },
                'filter'=> true,
            ],

            [
                'label' => '班内人数',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) {
                    return count($model->groupStudents). ' '. Html::a('<span>详情</span>', ['groupstudent/index', 'group_id'=>$model->group_id]);
                },
            ],

            [
                'label' => '状态',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) {
					if ($model->status == MGroup::GROUP_STATUS_NONE)
	                    return MGroup::getGroupStatusOptionName($model->status). ' '. Html::a('<span>开班</span>', ['group/start', 'group_id'=>$model->group_id]);
					else if ($model->status == MGroup::GROUP_STATUS_SCHEDULE_DONE)
						return MGroup::getGroupStatusOptionName($model->status). ' '. Html::a('<span>结班</span>', ['group/end', 'group_id'=>$model->group_id]);
					else
						return MGroup::getGroupStatusOptionName($model->status);
                },
            ],

            [
                'label' => '班的课时教学计划',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) {
					if ($model->status == MGroup::GROUP_STATUS_NONE)
	                    return '请先开班后再查看计划详情';
					else
	                    return Html::a('<span>详情</span>', ['courseschedule/index', 'group_id'=>$model->group_id]);
                },
            ],

			[
				'label' => '班相册',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return $model->getPhotosCount().' '.Html::a('<span>详情</span>', ['myphotos/index', 'owner_cat'=>$model->ownerCat, 'owner_id'=>$model->getPrimaryKey()]);

				},
			],
			
//			'status',
            
			'create_time',

             ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

	<?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
/*
    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create', 'course_unit_id'=>$courseUnit->course_unit_id], ['class' => 'btn btn-success']) ?>
    </p>

*/