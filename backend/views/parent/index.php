<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\MSchool;

$this->title = Yii::t('backend', 'Parent');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mparent-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            //['class' => 'yii\grid\SerialColumn'],

            'parent_id',
            'student_id',
            'name',
		   [
				'attribute' => 'sex',
				'value'=>function ($model, $key, $index, $column) { return MSchool::getSexOptionName($model->sex); },
				'filter'=> MSchool::getSexOptionName(),
		   ],

            'mobile',
            // 'addr',
            // 'qq',
            // 'email:email',
            // 'is_delete',

			['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],

        ],
    ]); ?>

</div>
