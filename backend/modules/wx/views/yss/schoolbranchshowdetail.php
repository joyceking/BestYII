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

<title>校区介绍</title>

</head>

<body>

<div class="media">

    <?php
      use yii\helpers\Url;
      use backend\modules\wx\models\U;
      use common\models\MSchool;
      use common\models\MSchoolBranch;
      use common\models\MTeacher;
      use common\models\MPhoto;
      use common\models\MPhotoOwner;
      use common\models\MMapApi;

      $photo = $schoolbranch->getPhoto(''); 
    ?>

    <img src="<?php echo Url::to($photo->getPicUrl()) ?>" width="100%"  class="img-rounded">
    

    <br>

    <div class="media-body">
      <h4 class="media-heading"><?= $schoolbranch->title ?></h4>
      <p id="showMore"><?= $schoolbranch->des ?>
      <p><?= $schoolbranch->addr ?>
      <p><a href="tel:<?= $schoolbranch->mobile ?>"><?= $schoolbranch->mobile ?></p>
      
    </div>

    <p>
      <a href="http://backend.hoyatech.net/index.php?r=wx/yss/myreserve&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>&school_branch_id=<?php echo $schoolbranch->school_branch_id;?>" class="btn btn-success" role="button">&nbsp;我要预约</a>
      <a href="http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranchnav&school_branch_id=<?php echo $schoolbranch->school_branch_id;?>" class="btn btn-primary" role="button">我要导航</a>
    </p>

</div>

<br>
<p>
  <!--
  <a href="http://backend.hoyatech.net/index.php?r=wx/yss/myreserve&course_id=<?//php echo $course->course_id;?>" class="btn btn-success" role="button">&nbsp;我要预约</a>
  <a  class="btn btn-primary glyphicon glyphicon-play" role="button" id="v1DetailBtn">播放</a>
  -->
</p>


<br>
<h4>校区展示</h4>
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
      $photos = $schoolbranch->getPhotos('校区设施',6); 
      foreach ($photos as $photo) {
    ?>
      <a href="<?php echo Url::to($photo->getPicUrl()) ?>" title="<?= $photo->title ?>" data-gallery="" data-description="<?= $photo->des ?>">
          <img src="<?php echo Url::to($photo->getPicUrl(80,80)) ?>" alt="">
      </a>
  <?php
      }
  ?>
 
</div>
</center>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
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


  });


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




  var desc = '<?= strip_tags($schoolbranch->des) ?>';
  var shareData = {
    title: '<?= $schoolbranch->title ?>详情',
    desc: desc,
    link: 'http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranchshowdetail&gh_id=<?php echo $gh_id;?>&openid=nobody&school_branch_id=<?php echo $schoolbranch->school_branch_id;?>',
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





