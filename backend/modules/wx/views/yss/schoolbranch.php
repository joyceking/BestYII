<?php
  //require_once "jssdk.php";
  use backend\modules\wx\models\jssdk;
  $jssdk = new JSSDK("wx4190748b840f102d", "a5c3d42268d8b1a470fad26f9808198e");
  $signPackage = $jssdk->GetSignPackage();

    use yii\helpers\Url;
    use backend\modules\wx\models\U;
    use common\models\MSchool;
    use common\models\MSchoolBranch;
    use common\models\MTeacher;
    use common\models\MPhoto;
    use common\models\MPhotoOwner;
    use common\models\MMapApi;
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/> 
<title>校区查询</title>

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

    <ul id="dist_list" data-role="listview"  class="ui-nodisc-icon ui-alt-icon" data-filter="true" data-filter-placeholder="Search ..." data-inset="true">
  
    </ul>
  </div>

</div> <!-- page1 end -->


</body>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>

    var lat;
    var lng;

    var d_tmp=0;
    var ds_tmp='';

    var x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    var region = "<?= $_GET['region'] ?>";

   function Rad(d){
     return d * Math.PI / 180.0;//经纬度转换成三角函数中度分表形式。
  }
  //计算距离，参数分别为第一点的纬度，经度；第二点的纬度，经度
  function GetDistanceNum(lat1,lng1,lat2,lng2){

      var radLat1 = Rad(lat1);
      var radLat2 = Rad(lat2);
      var a = radLat1 - radLat2;
      var  b = Rad(lng1) - Rad(lng2);
      var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a/2),2) +
      Math.cos(radLat1)*Math.cos(radLat2)*Math.pow(Math.sin(b/2),2)));
      s = s *6378.137 ;// EARTH_RADIUS;
      //s = Math.round(s * 10000) / 10000; //输出为公里
      s = parseInt(Math.round(s * 10000) / 10000 * 1000); //输出为米
      //s=s.toFixed(4);
      return s;
  }



  function bindDist(lat,lng)
  {
    <?php
      $n = 1;
      foreach($schoolbranchs as $schoolbranch)
      {
        $photo = $schoolbranch->getPhoto('');
    ?>
        d = GetDistanceNum(lat,lng,'<?= $schoolbranch->lat ?>','<?= $schoolbranch->lon ?>');

        if(region == "<?= $schoolbranch->region ?>" )
        {
            ds = "<li><a data-ajax='false' href='http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranchshowdetail&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>&school_branch_id=<?php echo $schoolbranch->school_branch_id;?>'><img src='<?php echo Url::to($photo->getPicUrl(160,160)) ?>'><h2><?= $schoolbranch->title ?></h2><p style='color:#aaa'>距离" + d + "米</p></a></li>";

            if(d > d_tmp)
            {
              ds_tmp = ds_tmp + ds;
            }
            else
            {
               ds_tmp = ds + ds_tmp;
            }

            d_tmp = d;
            }
          <?php
            $n = $n + 1;
      }
      ?>

      $("#dist_list").html(ds_tmp);
      $("#dist_list").listview('refresh');
  }

function bindDistWithoutGEO()
{
    <?php
      $n = 1;
      foreach($schoolbranchs as $schoolbranch)
      {
        $photo = $schoolbranch->getPhoto('');
    ?>

    if(region == "<?= $schoolbranch->region ?>" )
    {
        ds = "<li><a data-ajax='false' href='http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranchshowdetail&gh_id=<?php echo $gh_id;?>&openid=<?php echo $openid;?>&school_branch_id=<?php echo $schoolbranch->school_branch_id;?>'><img src='<?php echo Url::to($photo->getPicUrl(160,160)) ?>'><h2><?= $schoolbranch->title ?></h2></a></li>";
        
        ds_tmp = ds_tmp + ds;
    }
    <?php
      $n = $n + 1;
    }
    ?>
    $("#dist_list").html(ds_tmp);
    $("#dist_list").listview('refresh');
}

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
    title: '校区查询',
    desc: '爱迪天才我们和您的孩子一起创造世界',
    link: 'http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranch&gh_id=<?php echo $gh_id;?>&openid=nobody',
    imgUrl: '<?php echo MPhoto::getUploadPicUrl("course.jpg") ?>'
  };


wx.ready(function () {

    wx.getLocation({
      success: function (res) {
        //alert(JSON.stringify(res));
        //alert("lon"+res.longitude+"lat"+res.latitude);
        lat = res.latitude;
        lng = res.longitude;

        /// 中国正常坐标系GCJ02协议的坐标，转到 百度地图对应的 BD09 协议坐标
        var z = Math.sqrt(lng * lng + lat * lat) + 0.00002 * Math.sin(lat * x_pi);
        var theta = Math.atan2(lat, lng) + 0.000003 * Math.cos(lng * x_pi);

        lng = z * Math.cos(theta) + 0.0065;
        lat = z * Math.sin(theta) + 0.006;

        bindDist(lat,lng);
      },
      cancel: function (res) {
          alert('用户拒绝授权获取地理位置');
          bindDistWithoutGEO();
      }
    });

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





