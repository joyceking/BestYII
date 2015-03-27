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

<script src="./js/wechat.min.js"></script>

<title>近期活动</title>
</head>

<style type="text/css">
  body{padding: 10px;}
  .wow{color:red; font-weight: bolder;}
</style>

<body>

<div class="media">
  <img src="./swiper/act1.jpg" alt="" width="100%" class="img-rounded">
  <br><br>
    <div class="media-body">
      <h4 class="media-heading">
      <font color=red>
      爱迪天才光谷校区，即将欢乐入驻光谷德意风情街三楼，让我们拭目以待吧！
      </font>
      </h4>
      <br>

      <center>
      <p>
        光谷校区乔迁啦！<br>
        <span class="wow">庆乔迁，送现金</span>，就是这么任性！<br>
        凡2014年12月及2015年1月报课会员，<br>
        即可参与<span class='wow'>微信抽红包</span>一次，<br>
        抽到红包<span class='wow'>当场兑现</span>！<br>
        最高可获<span class='wow'>800元</span>红包。<br>
        小手抖一抖，现金拿到手！<br>
        （详情店内咨询）<br>
      </p>
      </center>
    </div>
</div>

<br>
<a href='tel:4000999027' class="btn btn-success btn-lg btn-block">热线联系我们</a>

<hr>


<br>

<script type="text/javascript">
WeixinApi.ready(function(Api) {
  Api.showOptionMenu();
  var wxData = {
    "imgUrl" : 'http://backend.hoyatech.net/swiper/act1.jpg',
    "desc" : '爱迪天才光谷校区，即将欢乐入驻光谷德意风情街三楼，让我们拭目以待吧！',
    "title" : '爱迪天才近期活动',
    "link" : 'http://backend.hoyatech.net/index.php?r=wx%2Fyss%2Factivities'
    };
  // 分享的回调
  var wxCallbacks = {
    // 分享被用户自动取消
    cancel : function(resp) {
      //alert("亲，这么好的东西怎么能不分享给好朋友呢！");
    },
    // 分享失败了
    fail : function(resp) {
      //alert("分享失败，可能是网络问题，一会儿再试试？");
    },
    // 分享成功
    confirm : function(resp) {
      window.location.href='';
    },
  };
  Api.shareToFriend(wxData,wxCallbacks);
  Api.shareToTimeline(wxData,wxCallbacks);
  Api.shareToWeibo(wxData,wxCallbacks);
});
</script>
</body>
</html>





