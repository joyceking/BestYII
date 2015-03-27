<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MSubcourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Msubcourses');
$this->params['breadcrumbs'][] =  ['label'=>"课程".'['.$course->title.']', 'url'=>['/course']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="msubcourse-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        
        'columns' => [
            
            'subcourse_id',
            
//            'course_id',
            
            'title',
			[
				'label' => '包含的课时',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return count($model->subcourseCourseUnits).' '.Html::a('<span>详情</span>', ['subcoursecourseunit/index', 'subcourse_id'=>$model->subcourse_id]);
				},
			],
            

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],

        ],
    ]); ?>


	<?= $this->render('_form', [
        'model' => $model,
        'course' => $course,
    ]) ?>

</div>
