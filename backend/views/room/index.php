<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('backend', 'Room');
$this->params['breadcrumbs'][] =  ['label'=>$schoolBranch->school->title, 'url'=>['/school']];
$this->params['breadcrumbs'][] =  ['label'=>$schoolBranch->title, 'url'=>['/schoolbranch', 'school_id'=>$schoolBranch->school->school_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mroom-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create', 'school_branch_id'=>$schoolBranch->school_branch_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'room_id',
			[
				'attribute' => 'school_branch_id',
				'headerOptions' => array('style'=>'width:80px;'),	
				'filter'=> false,				
			],

            'title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
