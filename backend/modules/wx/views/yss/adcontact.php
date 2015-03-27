<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\MOpenidOwner;
use common\models\MPhoto;
use \yii\widgets\ListView;
use yii\grid\GridView;
use common\models\MReserve;

use backend\modules\wx\models\jssdk;
?>

<?php
	//require_once "jssdk.php";

	$jssdk = new JSSDK("wx4190748b840f102d", "a5c3d42268d8b1a470fad26f9808198e");
	$signPackage = $jssdk->GetSignPackage();
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

<title>联系我们</title>

</head>

<style type="text/css">
  body{padding: 10px;}
</style>

<body>

<img src='./swiper/head1.jpg' width='100%' class="img-rounded">
<br><br>

<div class="form-group">
<!--
  爱迪天才专注2-9岁儿童思维培养专家! <br>
  爱迪天才我们和您的孩子一起创造世界! <br>
  育儿热线4-000-999-027!
-->
<?php echo $keyword["value"]; ?>

</div>

<a href='tel:4000999027' class="btn btn-success btn-lg btn-block">4-000-999-027 热线我们</a>

<!--
<br>
<a href='#' id='chooseImage' class="btn btn-primary btn-lg btn-block">chooseImage</a>
-->

</body>
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
		    'onMenuShareWeibo',
			'chooseImage'
			//'previewImage',
			//'uploadImage',
			//'downloadImage'
		]
	});

	var desc = '<?= strip_tags($keyword["value"])?>';
	var shareData = {
		title: '联系我们',
		desc: desc,
		link: 'http://backend.hoyatech.net/index.php?r=wx/yss/adcontact',
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

	  var images = {
	    localId: [],
	    serverId: []
	  };
	  document.querySelector('#chooseImage').onclick = function () {
	    wx.chooseImage({
	      success: function (res) {
	        images.localId = res.localIds;
	        alert('已选择 ' + res.localIds.length + ' 张图片');
	        //alert(res.localIds);
	      }
	    });
	  };


});

</script>


</html>





