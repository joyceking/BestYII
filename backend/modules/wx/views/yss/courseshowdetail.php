<?php
  //require_once "jssdk.php";
  use backend\modules\wx\models\jssdk;
  $jssdk = new JSSDK("wx4190748b840f102d", "a5c3d42268d8b1a470fad26f9808198e");
  $signPackage = $jssdk->GetSignPackage();
?>


<!DOCTYPE html>
<html lang="zh-CN">

<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css?v1">

<link rel="stylesheet" href="./swiper/blueimp-gallery.min.css">
<link rel="stylesheet" href="./swiper/bootstrap-image-gallery.min.css">

<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js?v1"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js?v1"></script>

<script src="./swiper/jquery.blueimp-gallery.min.js"></script>
<script src="./swiper/bootstrap-image-gallery.min.js"></script>


<style type="text/css">
  body{padding: 10px;}

  p{font-size: 12pt;}

  img{padding: 2px;}

  .blueimp-gallery > .description {
    position: absolute;
    top: 40px;
    left: 15px;
    color: #fff;
    display: none;
  }
  .blueimp-gallery-controls > .description {
    display: block;
  }
</style>

<title>课程介绍</title>

</head>

<body>

<div class="media">

    <?php
      use yii\helpers\Url;
      use common\models\MCourse;
      use common\models\MPhoto;
      use common\models\MPhotoOwner;

      $photo = $course->getPhoto(''); 
    ?>

    <img src="<?php echo Url::to($photo->getPicUrl()) ?>" width="100%"  class="img-rounded">
    
    <br>

    <div class="media-body">
      <h4 class="media-heading"><?= $course->title ?></h4>

      <p id="contactIcon1"><?= $course->object ?>
      &nbsp;
      <span style='color:#8C0C84' class="glyphicon glyphicon-chevron-down"></span>
      </p>

    <span class="p1" id='contactIcon2'>
      <p><?= $course->des ?>
      &nbsp;&nbsp;
      <span id='contactIcon2' style='color:#8C0C84' class="glyphicon glyphicon-chevron-up"></span>
      </p>
    </span>

    </div>
</div>

<br>
<p>
  <a href="http://backend.hoyatech.net/index.php?r=wx/yss/myreserve&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>&course_id=<?php echo $course->course_id;?>" class="btn btn-success" role="button">&nbsp;我要预约</a>
    <?php   $vedio = $course->getPhoto('视频',1);  ?>

    <?php if (!empty($vedio)) { ?>
    <a  class="btn btn-primary glyphicon glyphicon-play" role="button" id="v1DetailBtn">播放</a>
    <?php } ?>
</p>


<br>
<h4>课堂动态</h4>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-use-bootstrap-modal="false" >
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
     <!-- The placeholder for the description label: -->
    <p class="description"></p>

    <!--
    <a class="prev">‹</a>
    <a class="next">›</a>
    -->

    <a class="close">×</a>
    <!--
    <a class="play-pause"></a>
    -->

    <!--
    <ol class="indicator"></ol>
    -->


    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-warning next">
                        Next 
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<center>

<div id="links">

  <?php
    use common\models\U;

    use common\models\MSchool;
    use common\models\MSchoolBranch;
    use common\models\MTeacher;

    $i = 1;

    $students = $course->students;

    foreach($students as $student)
    {
      $photos = $student->getPhotos(''); 
      foreach ($photos as $photo) {
         if($i>6) break;
    ?>

        <a href="<?php echo Url::to($photo->getPicUrl()) ?>" title="<?= $photo->title ?>" data-gallery="" data-description="<?= $photo->des ?>">
            <img src="<?php echo Url::to($photo->getPicUrl(80,80)) ?>" alt="">
        </a>

  <?php
       $i = $i + 1;
      }
    }
  ?>

 
</div>
</center>


  <?php 
    $show = false;
    yii\bootstrap\Modal::begin([
      'header' => '<h4>'.$course->title.'视频</h4>',
      'options' => [
         'id' => 'showPlayerBox',
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
    <p class='v1'>
        <video width='100%'  id="demo1" controls>
        <!--<source src="pathtovideo/video.ogv" type="video/ogg"> -->
            <?php if (!empty($vedio)) { ?>
                <source src="<?= $vedio->getPicUrl() ?>" type="video/mp4">
                Your browser does not support the video tag.
            <?php } ?>
        </video>
    </p>
  </div>

  <?php yii\bootstrap\Modal::end(); ?>


<script type="text/javascript">
  
  $(document).ready(function($) {

    blueimp.Gallery(
        document.getElementById('links'), {
            onslide: function (index, slide) {
                var text = this.list[index].getAttribute('data-description'),
                    node = this.container.find('.description');
                node.empty();
                if (text) {
                    node[0].appendChild(document.createTextNode(text));
                }
            }
    });

    document.getElementById('links').onclick = function (event) {
        event = event || window.event;
        var target = event.target || event.srcElement,
            link = target.src ? target.parentNode : target,
            options = {index: link, event: event, onslide: function (index, slide) {
                var text = this.list[index].getAttribute('data-description'),
                    node = this.container.find('.description');
                node.empty();
                if (text) {
                    node[0].appendChild(document.createTextNode(text));
                }
            } },
            links = this.getElementsByTagName('a');
        blueimp.Gallery(links, options);
    };


    $("#v1DetailBtn").click(function(){
      $('#showPlayerBox').modal('show');
    });  


    $('.p1').hide();

    $('#contactIcon1').click(function()
    {
      $('.p1').show();

    });

     $('#contactIcon2').click(function()
    {
      $('.p1').hide();
    });

  });

</script>


<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">

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
    title: '<?= $course->title ?>课程介绍',
    desc: '<?= $course->object ?>',
    link: 'http://backend.hoyatech.net/index.php?r=wx/yss/courseshowdetail&gh_id=<?php echo $gh_id;?>&openid=nobody&course_id=<?php echo $course->course_id;?>',
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





