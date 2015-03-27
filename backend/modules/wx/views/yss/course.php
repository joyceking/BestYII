<?php
  //require_once "jssdk.php";
  use backend\modules\wx\models\jssdk;
  $jssdk = new JSSDK("wx4190748b840f102d", "a5c3d42268d8b1a470fad26f9808198e");
  $signPackage = $jssdk->GetSignPackage();
?>


<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/> 
<title>课程介绍</title>

<link href="http://cdn.bootcss.com/jquery-mobile/1.4.3/jquery.mobile.min.css" rel="stylesheet">
<script src="./swiper-new/dist/jquery-1.10.1.min.js"></script>
<script src="http://cdn.bootcss.com/jquery-mobile/1.4.3/jquery.mobile.min.js"></script>

<style>
/* Demo Styles */
html {
  height: 100%;
}
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13px;
  line-height: 1.5;
  position: relative;
  height: 100%;
}
</style>
</head>
<body>


<div data-role="page" id="page1">

  <div data-role="content">

    <ul data-role="listview" class="ui-nodisc-icon ui-alt-icon" data-filter="true" data-filter-placeholder="Search ..." data-inset="true">
   
         <?php
            use yii\helpers\Url;
            use backend\modules\wx\models\U;
            use common\models\MSchool;
            use common\models\MSchoolBranch;
            use common\models\MTeacher;
            use common\models\MPhoto;
            use common\models\MPhotoOwner;

            foreach($courses as $course)
            {           
              $photos = $course->getPhotos(''); 
              foreach ($photos as $photo) {     
            ?>
               <li>
                <a data-ajax='false' href="http://backend.hoyatech.net/index.php?r=wx/yss/courseshowdetail&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>&course_id=<?php echo $course->course_id;?>">
                  <img src="<?php echo Url::to($photo->getPicUrl(160,160)) ?>">

                  <h2><?= $course->title ?></h2>
                  <p><?= $course->object ?></p>
                </a>
              </li>
          <?php
              }
            }
          ?>
    </ul>
  </div>

</div> <!-- page1 end -->


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
        'onMenuShareWeibo'
    ]
  });


  var shareData = {
    title: '课程介绍',
    desc: '爱迪天才我们和您的孩子一起创造世界',
    link: 'http://backend.hoyatech.net/index.php?r=wx/yss/course&gh_id=<?php echo $gh_id;?>&openid=nobody',
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
</html>