<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\MSchool;


$this->title = Yii::t('backend', 'Teacher');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mteacher-index">
    <!--
    <h1><?//= Html::encode($this->title) ?></h1>
   -->
   
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'teacher_id',
			[
				'label' => false,
				'attribute' => 'head_url',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					 return Html::a(Html::img($model->headUrl, ['width'=>'64']), $model->headUrl);
				},
				'filter'=> false,
			],

            'name',
			[
				'attribute' => 'sex',
				'value'=>function ($model, $key, $index, $column) { return MSchool::getSexOptionName($model->sex); },
				'filter'=> MSchool::getSexOptionName(),
			],

            'birthday',
			[
				'attribute' => 'nationality',
				'value'=>function ($model, $key, $index, $column) { return MSchool::getNationalityOptionName($model->nationality); },
				'filter'=> MSchool::getNationalityOptionName(),
			],
			[
				'label' => '所授课程',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					$courses = $model->getCourses();
					$items = [];
					foreach($courses as $course) {
//						$items[] = Html::a($course->title, ['teachercourse', 'teacher_id'=>$model->teacher_id, 'course_id'=>$course->course_id]);
						$items[] = $course->title;
					}
					return empty($items) ? '' : implode(',', $items);
				},
			],

			[
				'label' => '相册',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return $model->getPhotosCount().' '.Html::a('<span>详情</span>', ['myphotos/index', 'owner_cat'=>$model->ownerCat, 'owner_id'=>$model->getPrimaryKey()]);

				},
			],

            // 'identify_id',
            // 'onboard_time',
            // 'des:ntext',
            // 'sort_order',
            // 'create_time',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
