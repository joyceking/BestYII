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
use common\models\MCourseScheduleSignon;


use yii\grid\GridView;
use common\models\MSchool;
use \yii\widgets\ListView;

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

<title>课时签到</title>

</head>

<style type="text/css">
  body{padding: 10px;}
</style>

<body>


<img src='<?php echo MPhoto::getUploadPicUrl('mysigon.jpg') ?>' width='100%' class="img-rounded">
<br><br>

  <table class="table table-bordered">
		
		<?php foreach($courseSchedules as $courseSchedule) { ?>
        <tr class="success">
            <td>班级</td>
            <td colspan="3"><?= $courseSchedule->courseUnit->course->title ?></td>
        </tr>
        <tr class="success">
            <td>课时</td>
            <td colspan="3"><?= $courseSchedule->courseUnit->title ?></td>
        </tr>

		<?php foreach($courseSchedule->courseScheduleSignons as $signon) { ?>
        <tr>
			<td><?= $signon->signon_id ?></td>
			<td><?= $signon->student->name ?></td>
			<td>
				<?php
				 	if($signon->is_signon == MCourseScheduleSignon::SIGNON_STATUS_NONE) {
				?>
					<?php echo Html::a('签到', ['signon', 'signon_id'=>$signon->signon_id]); ?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php echo Html::a('缺课', ['signoff', 'signon_id'=>$signon->signon_id]); ?>
				<?php } else if($signon->is_signon == MCourseScheduleSignon::SIGNON_STATUS_YES) { ?>
						<span style='color:green' class='glyphicon glyphicon-ok'>
				<?php } else { ?>
						<span style='color:red' class='glyphicon glyphicon-remove'>
				<?php 
					}
				?>
			</td>
        </tr>
        <?php } ?>
        <?php } ?>
  </table>

<script>

	jQuery(document).ready(function() {
		

	});

</script>

</body>




</html>
<?php

/*
*
*
*
*/