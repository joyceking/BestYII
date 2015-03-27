<?php
use yii\helpers\Url;
use common\models\MPhoto;
use common\models\MPhotoOwner;
?>

<?php
	//require_once "jssdk.php";
	use backend\modules\wx\models\jssdk;
	$jssdk = new JSSDK("wx4190748b840f102d", "a5c3d42268d8b1a470fad26f9808198e");
	$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/> 
<title>关于爱迪</title>
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<link href="http://cdn.bootcss.com/jquery-mobile/1.4.3/jquery.mobile.min.css" rel="stylesheet">
<link rel='stylesheet' id='camera-css'  href='./camera/css/camera.css' type='text/css' media='all'> 

<script src="http://libs.useso.com/js/jquery/1.7.1/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/jquery-mobile/1.4.3/jquery.mobile.min.js"></script>

<script type='text/javascript' src='./camera/scripts/jquery.easing.1.3.js'></script> 
<script type='text/javascript' src='./camera/scripts/camera.min.js'></script> 

<style>
/* Demo Styles */
.ui-content {
	background-color: #F7A600!important;
	padding: 0px !important;
	border-width: 0;
	overflow: visible;
	overflow-x: hidden;
	height: 100% !important;
	/*padding: 1em;*/
}
.about_area {
	background-color: #FFB9F7!important;
	padding: 5px!important;
}
.contact
{
	font-size: 13pt;
	color: #000;
}

body {
	margin: 0;
	padding: 0;
}

.fluid_container {
	margin: 0 auto;
	max-width: 1000px;
	width: 100%;
}
</style>
</head>
<body>


<div data-role="page" id="page1">

  <div data-role="content">
  
	<img src='<?php echo MPhoto::getUploadPicUrl('logo.jpg') ?>' width='100%'>

	<div class="about_area">
		<span id='contactIcon1'>
		<?php echo $school->slogan; ?>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<span style='color:#8C0C84' class="glyphicon glyphicon-chevron-down"></span>
		</span>

		<span class="p2">
		<?php echo $school->des;?>
		</span>
		<br>

		<span class="p3" id='contactIcon2'>
		<?php echo $school->history;?>
		
		&nbsp;&nbsp;&nbsp;&nbsp;
		<span style='color:#8C0C84' class="glyphicon glyphicon-chevron-up"></span>
		</span>
	</div>


    <ul data-role="listview" data-filter="false" data-filter-placeholder="Search ..." data-inset="true" class="ui-nodisc-icon ui-alt-icon">
   
		<li>
		<a data-ajax='false' href="http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranch&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>">
		  <img src="<?php echo MPhoto::getUploadPicUrl('school-1.jpg') ?>">
		  <h2>校区介绍</h2>
		  <p></p>
		</a>
		</li>

		<li>
		<a data-ajax='false' href="http://backend.hoyatech.net/index.php?r=wx/yss/teachershow&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>">
		  <img src="<?php echo MPhoto::getUploadPicUrl('teacher.jpg') ?>">
		  <h2>教师风采</h2>
		  <p></p>
		</a>
		</li>
  
  		<li>
		<a data-ajax='false' href="http://backend.hoyatech.net/index.php?r=wx/yss/studentshow&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>">
		  <img src="<?php echo MPhoto::getUploadPicUrl('mybind.jpg') ?>">
		  <h2>爱迪宝贝秀</h2>
		  <p></p>
		</a>
		</li>

		<li>
		<a data-ajax='false' href="http://backend.hoyatech.net/index.php?r=wx/yss/course&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>">
		  <img src="<?php echo MPhoto::getUploadPicUrl('course.jpg') ?>">
		  <h2>课程介绍</h2>
		  <p></p>
		</a>
		</li>

		<!--
		<li>
		<a data-ajax='false' href="http://backend.hoyatech.net/index.php?r=wx/yss/activities">
		  <img src="<//?//php echo MPhoto::getUploadPicUrl('news.jpg') ?>">
		  <h2>近期活动</h2>
		  <p></p>
		</a>
		</li>
		-->

    </ul>
    <div class="fluid_container">
        <div class="camera_wrap camera_azure_skin" id="camera_wrap_1">

        	<?php
	        	$photos = $school->getPhotos('团队风采',6); 
	        	foreach ($photos as $photo) {
        	?>
            <div data-src="<?= $photo->getPicUrl() ?>">
                <div class="camera_caption fadeFromBottom">
                   <?= $photo->des ?>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
    <br>
	<a href="tel:4000999027"  class="ui-btn ui-corner-all" style="background-color: #FFB9F7">
		<span class="glyphicon glyphicon-phone-alt"></span>&nbsp;&nbsp;
		<span class='contact'>4-000-999-027 免费热线</span>
	</a>
  </div>
  <div data-role="footer" data-position="fixed">
  	<h6>&copy;2015 爱迪天才</h6>
  </div>
</div> <!-- page1 end -->


<script type="text/javascript">

  $(document).ready(function($) {

        $('.p2').hide();
        $('.p3').hide();

        $('#contactIcon1').click(function()
        {
			$('.p2').show();
			$('.p3').show();
        });


         $('#contactIcon2').click(function()
        {
	        $('.p2').hide();
	        $('.p3').hide();
        });

        $('#camera_wrap_1').camera({
				//height: '90px',
				thumbnails: false,
				pagination: false
		});
    
  });

</script>


<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>

	wx.config({
		appId: '<?php echo $signPackage["appId"];?>',
		timestamp: '<?php echo $signPackage["timestamp"];?>',
		nonceStr: '<?php echo $signPackage["nonceStr"];?>',
		signature: '<?php echo $signPackage["signature"];?>',
		jsApiList: [
		  // 所有要调用的 API 都要加到这个列表中
		    'onMenuShareTimeline',
		    'onMenuShareAppMessage',
		    'onMenuShareQQ',
		    'onMenuShareWeibo'
		]
	});


	var desc = '<?php echo $school->slogan; ?>';
	var shareData = {
		title: '关于爱迪',
		desc: desc,
		link: 'http://backend.hoyatech.net/index.php?r=wx/yss/adabout&gh_id=<?php echo $gh_id;?>&openid=nobody',
		imgUrl: '<?php echo MPhoto::getUploadPicUrl("course.jpg") ?>'
	};


wx.ready(function () {
    wx.onMenuShareAppMessage({
      title: 'xxx',
      desc: 'xxx',
      link: 'xxx',
      imgUrl: 'xxx',
      trigger: function (res) {
        alert('用户点击发送给朋友');
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

    wx.onMenuShareTimeline({
      title: 'xxx',
      link: 'xxx',
      imgUrl: 'xxx',
      trigger: function (res) {
        alert('用户点击分享到朋友圈');
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

    wx.onMenuShareQQ({
      title: 'xxx',
      desc: 'xxx',
      link: 'xxx',
      imgUrl: 'xxx',
      trigger: function (res) {
        alert('用户点击分享到QQ');
      },
      complete: function (res) {
        alert(JSON.stringify(res));
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

    wx.onMenuShareWeibo({
      title: 'xxx',
      desc: 'xxx',
      link: 'xxx',
      imgUrl: 'xxx',
      trigger: function (res) {
        alert('用户点击分享到微博');
      },
      complete: function (res) {
        alert(JSON.stringify(res));
      },
      success: function (res) {
        alert('已分享');
      },
      cancel: function (res) {
        alert('已取消');
      },
      fail: function (res) {
        alert(JSON.stringify(res));
      }
    });

  wx.onMenuShareAppMessage(shareData);
  wx.onMenuShareTimeline(shareData);
});

</script>
</body>
</html>