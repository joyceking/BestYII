<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use common\models\MOpenidOwner;
use common\models\MPhoto;
use common\models\MStudent;
use common\models\MTeacher;

use \yii\widgets\ListView;
use yii\grid\GridView;
?>



<?php
  $this->registerJs(
	 '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
	 yii\web\View::POS_READY
  );
?>

<img src='<?php echo MPhoto::getUploadPicUrl('mybind1.jpg') ?>' width='100%' class="img-rounded">

<br>

<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-success flash-success">
	   <?php echo Yii::$app->session->getFlash('success'); ?>
  </div>
<?php endif; ?>

<h4>绑定管理</h4>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	//'filterModel' => false,
	'showOnEmpty'=>false,
	'showHeader'=>false,
	'layout' => "{items}\n{pager}",
	'columns' => [
		[
			'attribute' => 'owner_cat',
			'value'=>function ($model, $key, $index, $column) { return MOpenidOwner::getOpenidOwnerCatOptionName($model->owner_cat); },
		],
		[			
			'attribute' => 'owner_id',
		],
    [     
      'value'=>function ($model, $key, $index, $column) {
        if($model->owner_cat == MOpenidOwner::OPENID_OWNER_STUDENT)
        {
          $ar = MStudent::findOne(['student_id'=>$model->owner_id]);
        }
        else
        {
          $ar = MTeacher::findOne(['teacher_id'=>$model->owner_id]);
        }
        return $ar->name;
     },
    ],

    [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{mybinddelete}',
            'buttons' => [
                 'mybinddelete' => function ($url, $model) {
                      return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                      //return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(), [
                           'title' => Yii::t('app', 'Unbind'),
                           'data-confirm' => Yii::t('app', 'Unbind this ID, are you sure?'),
                           'data-method' => 'post',
                           'data-pjax' => '0',
                           //'data-pjax' => '1',
                      ]);
                 }
            ],
       ],

	],
]); ?>

<br />
<div class="mphoto-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'owner_cat')->dropDownList(MOpenidOwner::getOpenidOwnerCatOptionName()) ?> 

    <?= $form->field($model, 'owner_name')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 16])->label('电话(电话或ID任意输入一个即可)'); ?>

    <?= $form->field($model, 'owner_id')->textInput(['maxlength' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Bind') : Yii::t('backend', 'Update Bind'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => 'mybind_item',
    'layout' => "{summary}\n{items}\n{pager}\n",
]) ?>

*/

