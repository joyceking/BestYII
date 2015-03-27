<!DOCTYPE html>
<html lang="zh-CN">

<head>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<title>课程介绍</title>

<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">

<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

</head>

<style type="text/css">
  body{padding: 10px;}
  /*p{font-size: 12pt;}*/
</style>

<body>


  <?php
    use yii\helpers\Url;
    use common\models\MPhoto;
    use common\models\MPhotoOwner;
    $i = 1;

    foreach($courses as $course)
    {
      $photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_COURSE, $course->course_id, 100);
      foreach ($photos as $photo) {
    ?>
       <div class="media">
          <img src="<?php echo Url::to($photo->getPicUrl()) ?>" alt="" width="100%" class="img-rounded">
          <br><br>
            <div class="media-body">
              <h4 class="media-heading"><?= $course->title ?></h4>
              <p>课程目标：<?= $course->object ?></p>

              <p class='v<?= $i ?>'>
                  <video width='330 height=240' id="demo1" controls>
                  <!--<source src="pathtovideo/video.ogv" type="video/ogg"> -->
                  <source src="<?php echo MPhoto::getUploadPicUrl('dizzy.mp4') ?>" type="video/mp4">
                  Your browser does not support the video tag.
                  </video>
              </p>

              <p class='c<?= $i ?>'>
               <?= $course->des ?>
              </p>
              <p>
              <a href="tel:4000999027" class="btn btn-success glyphicon glyphicon-phone-alt" role="button">&nbsp;我要电话预约</a>
              <a  class="btn btn-primary glyphicon glyphicon-play" role="button" id="v<?= $i ?>DetailBtn">播放</a>
              <a  class="btn btn-default" role="button" id="c<?= $i ?>DetailBtn">详情>></a>

              </p>
            </div>
        </div>
        <hr>
  <?php
      $i = $i + 1;
      }
    }
  ?>

<br>

<script type="text/javascript">
  $(document).ready(function($) {

  <?php

    $j = 1;

    foreach($courses as $course)
    {
      $photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_COURSE, $course->course_id, 100);
      foreach ($photos as $photo) {
    ?>
        $('.c<?= $j ?>').hide();
        $('.v<?= $j ?>').hide();

        $("#c<?= $j ?>DetailBtn").click(function(){
            $('.c<?= $j ?>').toggle();
        });

        $("#v<?= $j ?>DetailBtn").click(function(){
            $('.v<?= $j ?>').toggle();
        });        
  <?php
      $j = $j + 1;
      }
    }
  ?>


  });

</script>

</body>
</html>





