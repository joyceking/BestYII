<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = Yii::t('backend', 'CourseUnit');
$this->params['breadcrumbs'][] =  ['label'=>$course->title, 'url'=>['/course']];
//$this->params['breadcrumbs'][] =  ['label'=>$schoolBranch->title, 'url'=>['/courseunit', 'school_id'=>$schoolBranch->school->school_id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php yii\widgets\Pjax::begin(['id' => 'id_pjax_course_unit']) ?>

<div class="mcourse-unit-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create', 'course_id' => $course->course_id], ['class' => 'btn btn-success']) ?>

		<?php if ($course->isAvailable()): ?>
			<?= Html::a(Yii::t('backend', 'Start Make Course Unit'), ['togglestatus', 'course_id' => $course->course_id], ['class' => 'btn btn-danger', 'data-confirm' => Yii::t('backend', 'Are you sure you want to start to make course unit?')]) ?>	
		<?php else: ?>
	        <?= Html::a(Yii::t('backend', 'Finish Make Course Unit'), ['togglestatus', 'course_id' => $course->course_id], ['class' => 'btn btn-success']) ?>
		<?php endif; ?>
    </p>

    <?= GridView::widget([
		'options'=>['id'=>'id_gridview'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'tableOptions' => ['class' => 'table table-striped'],        
        'columns' => [
			[
				'class' => 'yii\grid\CheckboxColumn',
			],

//            'sort_order',
            'course_unit_id',
//            'course_id',
            'title',
            'des:ntext',
            'prepare:ntext',
            // 'caution:ntext',
            'minutes',
/*
			[
				'label' => '此课时上的建班数量',
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
					return count($model->groups).' '.Html::a('<span>详情</span>', ['group/index', 'course_unit_id'=>$model->course_unit_id]);
				},
			],*/

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>


    <?= Html::a(Yii::t('backend', 'Batch Delete'), ['batchdelete'], ['id'=>'id_batch_delete', 'class' => 'btn btn-success']) ?>

</div>
<?php yii\widgets\Pjax::end() ?>

<?php
 
	$url = \yii\helpers\Url::to(['batchdelete']);
     $js = <<<EOD
$('#id_batch_delete').on('click',function() {
	if (confirm('批量删除, 确定?'))
	{
		var ids = $('#id_gridview').yiiGridView('getSelectedRows');
		$.ajax({
			type: "POST",
			url: '$url',
			data: {ids : ids},
			success: function(data) {
				if (data.status === 'success') {
					$.pjax.reload({container:'#id_gridview'});
				}
				else {
					alert('delete error!');
				}
			},
			dataType: 'json'
		});
	}
	return false;
});

EOD;

$this->registerJs($js)
?>



