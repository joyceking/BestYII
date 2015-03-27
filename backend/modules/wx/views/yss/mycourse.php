<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\MOpenidOwner;

use \yii\widgets\ListView;
use yii\grid\GridView;

use common\models\MCourse;
use common\models\MPhoto;
use common\models\MStudent;
use common\models\MCourseSchedule;
use common\models\MGroup;
use common\models\MTeacher;
use common\models\MRoom;
use common\models\MSubcourse;
use common\models\U;

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

<title>宝贝课表</title>

</head>

<style type="text/css">
  body{padding: 10px;}
</style>

<body>

<img src='<?php echo MPhoto::getUploadPicUrl('mycourse.jpg') ?>' width='100%' class="img-rounded">

<br><br>

  <table class="table table-bordered">
   <!-- <caption>宝贝课表一</caption> -->

    <?php foreach($students as $student) { ?>
        <tr class="info">
            <td width="20%">学生</td>
            <td><?php echo $student->name ?></td>
        </tr>

        <?php 
//          $schedulesX = $student->getCourseSchedulesX();
//          foreach($schedulesX as $group_id => $schedules) { 
			$schedulesX = $student->getCourseSchedulesY();
			foreach($schedulesX as $subcourse_id => $schedules) { 
        ?>

            <?php 
//              $group = MGroup::findOne($group_id);
//              $course = $group->course;
				$subcourse = MSubcourse::findOne($subcourse_id);
            ?> 

            <tr class="success">
                <td>班级</td>
                <td>
                <?php 
                //echo $course->title .'/'. $group->title;
                echo $subcourse->title;

                ?></td>
            </tr>

                <?php foreach($schedules as $courseSchedule) { ?>

                <?php 
                    $courseSchedule = $courseSchedule[0];
                  $teacher = MTeacher::findOne($courseSchedule->teacher_id);
                  $room = MRoom::findOne($courseSchedule->room_id);
                ?> 

                <tr>
                  <td rowspan='3'><?php echo $courseSchedule->courseUnit->title ?></td>
                   <td><?php echo empty($room->title)?'--':$room->title ?></td>
                </tr>
                <tr>
                  <td><?php echo empty($teacher->name)?'--':$teacher->name ?></td>
                </tr>
                <tr>
                  <td>开始时间<?php echo $courseSchedule->start_time ?></td>
                </tr>
                <?php } ?>

        <?php } ?>

    <?php } ?>
   
  </table>

</body>

</html>
<?php
/*
<?php foreach($students as $student) { ?>

  student_name:<?php echo $student->name ?> 


  <?php 
    $schedulesX = $student->getCourseSchedulesX();
    foreach($schedulesX as $group_id => $schedules) { 
  ?>

    group_id:<?php echo $group_id ?>
    <?php 
      $group = MGroup::findOne($group_id);
      $course = $group->course;
    ?> 

    course_id:<?php echo $course->course_id ?> 
    course_title:<?php echo $course->title ?> 
    <br />

    <?php foreach($schedules as $courseSchedule) { ?>

      <?php 
        $teacher = MTeacher::findOne($courseSchedule->teacher_id);
      ?> 

      course_schedule_id:<?php echo $courseSchedule->course_schedule_id ?> 
      course_unit_id:<?php echo $courseSchedule->course_unit_id ?> 
      teacher_id:<?php echo $courseSchedule->teacher_id ?> 
      teacher_name:<?php echo $teacher->name ?> 
      start_time:<?php echo $courseSchedule->start_time ?> <br />

    <?php } ?>
 
  <br />
  <?php } ?>
    <hr />
<?php } ?>



*/





