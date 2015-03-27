<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\MOpenidOwner;

use \yii\widgets\ListView;
use yii\grid\GridView;

use common\models\MReserve;

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

<title>预约报名</title>

</head>

<style type="text/css">
  body{padding: 10px;}
</style>

<body>

<!--
<//?//php
  $this->registerJs(
   '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   yii\web\View::POS_READY
  );
?>
-->


<img src='./swiper/head1.jpg' width='100%' class="img-rounded">
<br><br>
<?php echo $keyword["value"]; ?>

<!--
<//?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-success flash-success">
     <//?php echo Yii::$app->session->getFlash('success'); ?>
  </div>
<//?php endif; ?>
-->

<?= GridView::widget([
  'dataProvider' => $dataProvider,
  'showOnEmpty'=>false,
  'emptyText'=>'',
  'showHeader'=>false,
  'layout' => "{items}\n{pager}",
  'columns' => [
    //[
    //  'attribute' => 'reserve_id',
      //'value'=>function ($model, $key, $index, $column) { return MOpenidOwner::getOpenidOwnerCatOptionName($model->owner_cat); },
    //],
    
    [
      'attribute' => 'name',
      //'value'=>function ($model, $key, $index, $column) { return MOpenidOwner::getOpenidOwnerCatOptionName($model->owner_cat); },
    ],

    //[     
    //  'attribute' => 'school_branch_id',
    //],
    [
        'label' => '校区',
        //'attribute' => 'course.title',
        'value'=>function ($model, $key, $index, $column) { return (empty($model->schoolBranch->title) ? '' : $model->schoolBranch->title); },
        //'filter'=> true,
    ],
    [
          'label' => '课程名称',
          //'attribute' => 'course.title',
          'value'=>function ($model, $key, $index, $column) { return (empty($model->course->title) ? '' : $model->course->title); },
          'filter'=> true,
    ],

    //[
    //  'attribute' => 'create_time',
    //],

    //[
    //  'attribute' => 'status',
    //],

    [
      //'label' => '状态',
      //'format'=>'html',
      'value'=>function ($model, $key, $index, $column) {
          return MReserve::getReserveStatusOptionName($model->status);
      },
    ],

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

  ],
]); ?>

<br />

<div class="mreserve-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'school_branch_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\MSchoolBranch::find()->where(['school_id'=>\common\models\MSchool::getSchoolIdFromSession()])->all(),
        'school_branch_id',
        'title'
    ), []) ?>


    <?= $form->field($model, 'course_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\MCourse::find()->where(['school_id'=>\common\models\MSchool::getSchoolIdFromSession()])->all(),
        'course_id',
        'title'
    ),[]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 16, 'placeholder'=>'姓名'])->label(false); ?>

    <?= $form->field($model, 'sex')->dropDownList(MReserve::getSexOptionName())->label(false); ?>   

    <?= $form->field($model, 'age')->textInput(['maxlength' => 10, 'placeholder'=>'年龄'])->label(false); ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 64, 'placeholder'=>'手机号码'])->label(false); ?>

    <?= $form->field($model, 'memo')->textInput(['maxlength' => 256, 'placeholder'=>'备注'])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', '提交信息') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<!--
<form role="form">
  <div class="form-group">
  <label for="school">试听中心</label>
   <select class="form-control">
    <option>请选择:</option>
    <option>菱角湖万达校区</option>
    <option>光谷步行街校区</option>
    <option>光谷时代广场校区</option>
    <option>青山奥山世纪城校区</option>
  </select>
  </div>

  <div class="form-group">
    <label for="school">试听课程</label>
   <select class="form-control">
    <option>请选择:</option>
    <option>(2-4) 创意思维</option>
    <option>(4-6) HaBa数学</option>
    <option>(6-9) 快乐科学</option>
    <option>(3-18)夏恩英语</option>
  </select>
  </div>

  <div class="form-group">
    <label for="username" class="sr-only">宝宝姓名</label>
    <input type="text" class="form-control" id="username" placeholder="宝宝姓名">
  </div>
  
  <div class="form-group">
    <label for="age" class="sr-only">宝宝年龄</label>
    <input type="number" class="form-control" id="age" placeholder="宝宝年龄">
  </div>

  <div class="form-group">
    <label for="sex" class="sr-only">宝宝性别</label>
    <label class="radio-inline">
      <input type="radio" name="sex" id="sex" value="女宝宝"> 女宝宝
    </label>
    <label class="radio-inline">
      <input type="radio" name="sex" id="sex" value="男宝宝"> 男宝宝
    </label>
  </div>

  <div class="form-group">
    <label for="mobile" class="sr-only">手机号码</label>
    <input type="tel" class="form-control" id="mobile" placeholder="手机号码">
  </div>

  <div class="form-group">
    <label for="memo" class="sr-only">备注信息</label>
    <textarea class="form-control" rows="3" id="memo" placeholder="备注信息"></textarea>
  </div>

  <button type="submit" class="btn btn-success btn-lg btn-block">提交信息</button>
</form>
-->

</body>

</html>





