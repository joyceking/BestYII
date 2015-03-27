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

<title>课时签到</title>

</head>

<style type="text/css">
  body{padding: 10px;}
</style>

<body>


<img src='<?php echo MPhoto::getUploadPicUrl('mysigon.jpg') ?>' width='100%' class="img-rounded">
<br><br>

  <table class="table table-bordered">
		
        <tr class="success">
            <td>班级</td>
            <td colspan="3">沙雕1501班</td>
        </tr>
        <tr class="success">
            <td>课时</td>
            <td colspan="3">第一课</td>
        </tr>

        <tr>
			<td>1</td>
			<td>小敏</td>
			<td>
				<a href='#' id="actShowDetailBox_<?= $i ?>">
					<span style='color:#ccc' class='glyphicon glyphicon-check'>
				</a>
			</td>
        </tr>
        <tr>
			<td>2</td>
			<td>小梦</td>
			<td>
				<a href='#' id="actShowDetailBox_<?= $i ?>">
					<span style='color:#green' class='glyphicon glyphicon-check'>
				</a>
			</td>
        </tr>
        
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