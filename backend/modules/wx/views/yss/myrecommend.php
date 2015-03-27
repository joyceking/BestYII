<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\MOpenidOwner;

use \yii\widgets\ListView;
use yii\grid\GridView;

use common\models\MRecommend;
use common\models\MPhoto;

?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 可选的Bootstrap主题文件（一般不用引入） -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<title>推荐有礼</title>

</head>

<style type="text/css">
  body{padding: 10px;}
</style>

<body>
<?php
  $this->registerJs(
   '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   yii\web\View::POS_READY
  );
?>


<img src='<?php echo MPhoto::getUploadPicUrl('myrecommend.jpg') ?>' width='100%' class="img-rounded">

<br><br>
<?php echo $keyword["value"]; ?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-success flash-success">
     <?php echo Yii::$app->session->getFlash('success'); ?>
  </div>
<?php endif; ?>
<hr>
<h5>我的推荐</h5>
<?= GridView::widget([
  'dataProvider' => $dataProvider,
  'showOnEmpty'=>false,
  'showHeader'=>false,
  'layout' => "{items}\n{pager}",
  'columns' => [
    //[
    //  'attribute' => 'recommend_id',
      //'value'=>function ($model, $key, $index, $column) { return MOpenidOwner::getOpenidOwnerCatOptionName($model->owner_cat); },
    //],
    
    [
      'attribute' => 'name',
    ],

    [
      'attribute' => 'mobile',
    ],

    //[
    //  'attribute' => 'status',
    //],

    [
        //'label' => '状态',
        //'format'=>'html',
        'value'=>function ($model, $key, $index, $column) {
            return MRecommend::getRecommendStatusOptionName($model->status);
        },
    ],

    /*
    [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{myreservedelete}',
            'buttons' => [
                 'myreservedelete' => function ($url, $model) {
                      return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                      //return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(), [
                           'title' => Yii::t('app', 'Delete'),
                           'data-confirm' => Yii::t('app', 'Delete reserve, are you sure?'),
                           'data-method' => 'post',
                           'data-pjax' => '0',
                           //'data-pjax' => '1',
                      ]);
                 }
            ],
       ],
       */

  ],
]); ?>

<hr>

<div class="mrecommend-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 16, 'placeholder'=>'被推荐人姓名'])->label(false); ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 64, 'placeholder'=>'被推荐人手机号码'])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', '立即推荐') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</body>

</html>





