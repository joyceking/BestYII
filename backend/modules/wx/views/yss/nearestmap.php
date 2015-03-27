<?php
	use backend\modules\wx\models\U;
	use common\models\MSchool;
	use common\models\MSchoolBranch;
	use common\models\MMapApi;
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>附近校区</title>

<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 可选的Bootstrap主题文件（一般不用引入） -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo MMapApi::getJsak(); ?>"></script>


<script type="text/javascript">

$(document).ready(function(){
	var lat = "<?php echo $lat_end; ?>";
	var lng = "<?php echo $lon_end; ?>";
	initBMap(lng,lat); //目的地
});

//走路检索
var walking = function(pointA,pointB,map){	
    var walking = new BMap.WalkingRoute(map, {renderOptions: {map: map, panel: "result", autoViewport: true}});
    walking.search(pointA, pointB);
};

//公交检索
var bus = function(pointA,pointB,map){	
    var transit = new BMap.TransitRoute(map, {renderOptions: {map: map, panel: "result", autoViewport: true}});
    transit.search(pointA, pointB);
};

//驾车检索
var driver = function(pointA,pointB,map){
	var transit = new BMap.DrivingRoute(map, {
         renderOptions: {
				map: map,
				panel: "result",
				enableDragging : true //起终点可进行拖拽
			},  
 	});
	transit.search(pointA,pointB);
};

var initBMap = function(lng1,lat1){	
	var map = new BMap.Map("map_container");
	map.centerAndZoom(pointB,16);
	var pointB = new BMap.Point(lng1,lat1);
	map.centerAndZoom(pointB,16);
	map.addControl(new BMap.NavigationControl());	
	var pointA = new BMap.Point("<?php echo $lon_begin; ?>","<?php echo $lat_begin; ?>");	//自己在的位置	
	if(map.getDistance(pointA,pointB) > 5000)
	{
		//大于5公里的驾车检索
		driver(pointA,pointB,map);
	}
	else if(map.getDistance(pointA,pointB) > 1000)
	{
		//大于1公里的公交检索
		bus(pointA,pointB,map);
	}
	else
	{
		walking(pointA,pointB,map);
	}
};
</script>

<style type="text/css">
#map_container{margin:0px;width:100%;height:200px;}
#result{height:100%;width:100%;}
.panel-body img{max-width:100%;}
</style>
</head>
<body>
<div id="map_container"></div>


<div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo $schoolbranch->title; ?></h3>
        </div>
        <div class="panel-body">
          	名称：<?php echo $schoolbranch->title; ?>
          	<br />
          	电话：<a href="tel:<?php echo $schoolbranch->mobile; ?>"><?php echo $schoolbranch->mobile; ?></a>
          	<br />
			地址：<?php echo $schoolbranch->addr; ?>
			<a href="<?php echo "http://api.map.baidu.com/direction?origin=latlng:{$lat_begin},{$lon_begin}|name:我的位置&destination=latlng:{$lat_end},{$lon_end}|name:{$schoolbranch->title}&mode=driving&region=武汉&output=html&src=wosotech|wosotech"; ?>">我要导航</a>
			<br/>
   	    </div>
</div>
<div id="result"></div>
</body>
</html>

