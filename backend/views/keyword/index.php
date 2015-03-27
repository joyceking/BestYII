<?php

use yii\helpers\Html;
use yii\grid\GridView;


use yii\widgets\ActiveForm;

$this->title = Yii::t('backend', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<ul class="nav nav-tabs">
     <?php //$currentMonth = date("n"); ?>
     <li <?php echo true ? 'class="active"' : ''; ?> >
     <?php echo Html::a("第三方接口参数", ['index'], []) ?>
     </li>

<?php
/*
     <li <?php //echo true ? 'class="active"' : ''; ?> >
     <?php echo Html::a("预约提示语", ['reserve'], []) ?>
     </li>
*/
?>
</ul>

<br />

<div class="mphoto-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'layout' => "{items}\n{pager}",

//        'filterModel' => $searchModel,
        
		'columns' => [
            
//			['class' => 'yii\grid\SerialColumn'],
//            'keyword_id',
//            'keyword',
			[
                'label' => '参数名',
                'attribute' => 'keyword',
                'value'=>function ($model, $key, $index, $column) { return $model->comment; },
                'filter'=> true,
            ],

			[
                'label' => '值',
                'attribute' => 'value',
                'value'=>function ($model, $key, $index, $column) { return $model->value; },
                'filter'=> true,
            ],

//            'value:ntext',
//            'create_time',
//            'update_time',

//            ['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],

        ],
    ]); ?>


</div>


<?php
/*
<ul class="nav nav-tabs">
     <?php $currentMonth = date("n"); ?>
     <li <?php echo $month == $currentMonth ? 'class="active"' : ''; ?> >
     <?php echo Html::a("渠道{$currentMonth}月成绩排行", ['channelscoretop', 'month'=>1], []) ?>
     </li>

     <?php $currentMonth = date("n", strtotime('-1 month', time())); ?>
     <li <?php echo $month == $currentMonth ? 'class="active"' : ''; ?> >
     <?php echo Html::a("渠道{$currentMonth}月成绩排行", ['channelscoretop', 'month'=>$currentMonth], []) ?>
     </li>

     <?php $currentMonth = date("n", strtotime('-2 month', time())); ?>
     <li <?php echo $month == $currentMonth ? 'class="active"' : ''; ?> >
     <?php echo Html::a("渠道{$currentMonth}月成绩排行", ['channelscoretop', 'month'=>$currentMonth], []) ?>
     </li>
</ul>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
		'columns' => [
            
			['class' => 'yii\grid\SerialColumn'],
            'keyword_id',
            'keyword',
            'value:ntext',
            'create_time',
            'update_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>



<ul class="nav nav-tabs">
     <?php $currentMonth = date("n"); ?>
     <li <?php echo true ? 'class="active"' : ''; ?> >
     <?php echo Html::a("第三方接口参数", ['index'], []) ?>
     </li>

</ul>

<div class="mgroup-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => 256, 'readonly'=>true]) ?>
   
	<?= $form->field($model, 'des')->label('课程')->textInput(['maxlength' => 256, 'value'=>$model->course->title, 'readonly'=>true]) ?>    

	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Save') : Yii::t('backend', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


*/