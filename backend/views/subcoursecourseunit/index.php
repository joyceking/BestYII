<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MSubcourseCourseUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Msubcourse Courseunits');
$this->params['breadcrumbs'][] =  ['label'=>"课程".'['.$subcourse->course->title.']', 'url'=>['/course']];
$this->params['breadcrumbs'][] =  ['label'=>"子课程".'['.$subcourse->title.']', 'url'=>['/subcourse', 'course_id'=>$subcourse->course->course_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="msubcourse-courseunit-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        
        'columns' => [
            
//            'subcourse_id',
            
            'course_unit_id',

			[
				'label' => '课时名称',
				'value'=>function ($model, $key, $index, $column) { 
					return $model->courseUnit->title;
				},
			],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],

        ],
    ]); ?>

	<?= $this->render('_form', [
        'model' => $model,
        'subcourse' => $subcourse,
    ]) ?>

</div>
