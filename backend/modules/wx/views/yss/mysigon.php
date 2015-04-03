<?php
use yii\helpers\Url;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use common\models\MOpenidOwner;
use common\models\MStudent;
use common\models\MCourseSchedule;
use common\models\MGroup;
use common\models\MTeacher;
use common\models\MPhoto;
use common\models\MPhotoOwner;


use \yii\widgets\ListView;
use yii\grid\GridView;
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

<title>签到记录</title>
</head>
<style type="text/css">
  body{padding: 10px;}
  .info td,.success td,.table>tbody>tr>td{
  text-align:center;
  vertical-align: middle;
  }
</style>
<body>
<img src='<?php echo MPhoto::getUploadPicUrl('qdjl-tw.jpg') ?>' width='100%' class="img-rounded">
<br><br>

  <table class="table table-bordered">

    <?php 
    	$i = 1;
    	foreach($students as $student) 
    { ?>
        <tr class="info">
            <td width='20%'>学生</td>
            <td colspan="3"><?php echo $student->name ?></td>
        </tr>

		<?php 
			$signonsX = $student->getCourseScheduleSignonsX();
			foreach($signonsX as $group_id => $signons) { 
		?>
			<?php 
				$group = MGroup::findOne($group_id);
				$course = $group->course;
			?> 
            <tr class="success">
                <td>班级</td>
                <td colspan="3" style="text-align:center;vertical-align: middle;"><?php echo $course->title ?>/<?php echo $group->title ?></td>
            </tr>
			<?php foreach($signons as $signon) { ?>
				<?php 
			        //$courseSchedule = MCourseSchedule::findOne($signon->course_schedule_id);
					$courseSchedule = $signon->courseSchedule;
					//$teacher = MTeacher::findOne($courseSchedule->teacher_id);
				?> 
                <tr>
                   <td width="30%"><?php echo $courseSchedule->courseUnit->title ?></td>
              
				<?php 
					if($signon->is_signon == 0){
				?>
					<td bgcolor="white" width="10%">&nbsp;</td>
				<?php } else if($signon->is_signon == 1) {?>
					<td width="10%"><span style='color:green' class='glyphicon glyphicon-ok'></td>
				<?php } else if($signon->is_signon == 2) {?>
					<td width="10%"><span style='color:red' class='glyphicon glyphicon-remove'></td>
				<?php } else {?>
					<td bgcolor="white" width="10%">&nbsp;</td>
				<?php } ?>
                   <td width="35%">
                   <?php echo $courseSchedule->start_time ?>
                   </td>
                   <td width="25%">
                   <!-- 学生姓名 -->
                   <span class='student_<?= $i ?>'><?php echo $student->name ?></span>

                   <!-- 开始时间 -->
                   <span class='start_time_<?= $i ?>'><?php echo $courseSchedule->start_time ?></span>

                   <!-- 课程 -->
                   <span class='course_<?= $i ?>'><?php echo $courseSchedule->courseUnit->course->title ?></span>

                   <!-- 课时 -->
                   <span class='course_unit_<?= $i ?>'><?php echo $courseSchedule->courseUnit->title ?></span>

                   <!-- 老师 -->
                   <span class='teacher_<?= $i ?>'><?php echo empty($courseSchedule->teacher->name)?'--':$courseSchedule->teacher->name ?></span>
                   <!-- 时长 -->
                   <span class='course_unit_minutes_<?= $i ?>'><?php echo $courseSchedule->courseUnit->minutes ?></span>
                   <!-- 签到情况 -->
                   <span class='signon_<?= $i ?>'>
                   <?php 
                    if($signon->is_signon==1)
                    	echo "<span style='color:green' class='glyphicon glyphicon-ok'>&nbsp;已签到</span>";
                    else if($signon->is_signon==2)
                    	echo "<span style='color:red' class='glyphicon glyphicon-remove'>&nbsp;未签到</span>";
                    else
                    	echo '--';
                     ?>
                   </span>
                   <!-- 评语 -->
                   <span class='memo_<?= $i ?>'><?php echo $signon->memo ?></span>
            		<!-- 签到照片 -->
                   <span class='signon_photo_<?= $i ?>'>
						<?php
							$photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_SIGNON, $signon->signon_id, 1);
							//$photo = $photos[0];
							foreach ($photos as $photo) {
							?>
							<a href="<?php echo Url::to($photo->getPicUrl()) ?>" title="<?= $photo->title ?>" data-gallery="" data-description="<?= $photo->des ?>">
							    <img src="<?php echo Url::to($photo->getPicUrl(75,75)) ?>" alt="">
							</a>
						<?php
							}
						?>
					</span>

             
                   <a href='#' id="actShowDetailBox_<?= $i ?>">
                   <span class='glyphicon glyphicon-search'>&nbsp;详情
                   </a>
                   </td>
                </tr>
                
                <?php $i= $i + 1; } ?>

        <?php } ?>

    <?php } ?>
   
  </table>
	<?php 
		$show = false;
		yii\bootstrap\Modal::begin([
			'header' => '<h3>签到详情</h3>',
			'options' => [
			   'id' => 'showDetailBox',
	           'style' => 'opacity:0.95;',
			], 
			//'footer' => "&copy; <span style='color:#d71920'>demo</span> ".date('Y'),
			'size' => 'modal-sm',
			'clientOptions' => [
				'show' => false
			],
			'closeButton' => [
				'label' => '&times;',
	            //'label' => '',
			]
		]);
	?>

	<div>
		<!--<h2><font>课程详情</font><h2>-->
		 <table class="table table-bordered">
	        <tr class="info">
	            <td width='35%'>学生</td>
	            <td><span class='student'></span></td>
	        </tr>

	        <tr>
	            <td>课程</td>
	           	<td><span class='course'></span></td>
	        </tr>

	        <tr>
	            <td>课时</td>
	            <td><span class='course_unit'></span></td>
	        </tr>

	        <tr>
	            <td>老师</td>
	            <td><span class='teacher'></span></td>
	        </tr>

	        <tr>
	            <td>开始时间</td>
	            <td><span class='start_time'></span></td>
	        </tr>
	        <tr>
	            <td>时长(分钟)</td>
	            <td><span class='course_unit_minutes'></span></td>
	        </tr>
	        <tr>
	            <td>签到情况</td>
	            <td><span class='signon'></span></td>
	        </tr>
	        <tr>
	            <td>评语</td>
	            <td><span class='memo'></span></td>
	        </tr>
	        <tr>
	            <td>签到照片</td>
				<td><span class='signon_photo'></span></td>
	            <!--
	            <td>
		            <a href="<//?php echo MPhoto::getUploadPicUrl('signon-demo.jpg') ?>">
		            	<img src='<//?php echo MPhoto::getUploadPicUrl('signon-demo.jpg') ?>' width='100%'>
		            </a>
	            </td>
	            -->
	        </tr>

		 </table>
		<!--
		<?//= Html::button('我知道了 !', ['class' => 'btn btn-success btn-block btn-lg', 'name' => 'viewMyAct', 'id' => 'viewMyActBtn']) ?>
		-->
	</div>

	<?php yii\bootstrap\Modal::end(); ?>

<script>

	jQuery(document).ready(function() {
		
	    <?php 
    	$i = 1;
    	foreach($students as $student) 
    	{ ?>

		<?php 
			$signonsX = $student->getCourseScheduleSignonsX();
			foreach($signonsX as $group_id => $signons) { 
		?>
			<?php 
				$group = MGroup::findOne($group_id);
				$course = $group->course;
			?> 

			<?php foreach($signons as $signon) { ?>
				<?php 
			        $courseSchedule = MCourseSchedule::findOne($signon->course_schedule_id);
				?> 				
					$(".student_<?= $i ?>").hide();
					$(".course_<?= $i ?>").hide();
					$(".course_unit_<?= $i ?>").hide();
					$(".start_time_<?= $i ?>").hide();
					$(".teacher_<?= $i ?>").hide();
					$(".course_unit_minutes_<?= $i ?>").hide();
					$(".signon_<?= $i ?>").hide();
					$(".signon_photo_<?= $i ?>").hide();
					$(".memo_<?= $i ?>").hide();
					
					
					$("#actShowDetailBox_<?= $i ?>").click(function() {
						
						$('#showDetailBox .student').html($(".student_<?= $i ?>").html());
						$('#showDetailBox .course').html($(".course_<?= $i ?>").html());
						$('#showDetailBox .course_unit').html($(".course_unit_<?= $i ?>").html());
						$('#showDetailBox .start_time').html($(".start_time_<?= $i ?>").html());
						$('#showDetailBox .teacher').html($(".teacher_<?= $i ?>").html());
						$('#showDetailBox .course_unit_minutes').html($(".course_unit_minutes_<?= $i ?>").html());
						$('#showDetailBox .signon').html($(".signon_<?= $i ?>").html());
						$('#showDetailBox .signon_photo').html($(".signon_photo_<?= $i ?>").html());
						$('#showDetailBox .memo').html($(".memo_<?= $i ?>").html());

						$('#showDetailBox').modal('show');
					});

                <?php $i= $i + 1; } ?>

        <?php } ?>

    	<?php } ?>	

	});

</script>

</body>




</html>

<?php

/*

<?php foreach($students as $student) { ?>

	student_name:<?php echo $student->name ?> 
	<br />

	<?php 
		$signonsX = $student->getCourseScheduleSignonsX();
		foreach($signonsX as $group_id => $signons) { 
	?>

		group_id:<?php echo $group_id ?>
		<?php 
			$group = MGroup::findOne($group_id);
			$course = $group->course;
		?> 

		course_id:<?php echo $course->course_id ?> 
		course_title:<?php echo $course->title ?> 
		<br />

		<?php foreach($signons as $signon) { ?>
			<?php 
		        $courseSchedule = MCourseSchedule::findOne($signon->course_schedule_id);
				$teacher = MTeacher::findOne($courseSchedule->teacher_id);
			?> 

			course_schedule_id:<?php echo $courseSchedule->course_schedule_id ?> 
			course_unit_id:<?php echo $courseSchedule->course_unit_id ?> 
			teacher_id:<?php echo $courseSchedule->teacher_id ?> 
			teacher_name:<?php echo $teacher->name ?> 
			start_time:<?php echo $courseSchedule->start_time ?> 

			course_schedule_id:<?php echo $signon->course_schedule_id ?> 
			student_id:<?php echo $signon->student_id ?> 
			is_signon:<?php echo $signon->is_signon ?> 
			score:<?php echo $signon->score ?> <br/>

		<?php } ?>
 

	<?php } ?>
    <hr />


	$("#viewMyActBtn").click(function() {
		$('#showDetailBox').modal('hide');
	});   

*/