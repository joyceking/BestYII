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
<title>校区查询</title>

<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 可选的Bootstrap主题文件（一般不用引入） -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo MMapApi::getJsak(); ?>"></script>


<?php
	$school_branch_id = $_GET["school_branch_id"];
	$schoolbranch = MSchoolBranch::findOne($school_branch_id);
?>

<script type="text/javascript">

var lat = "<?php echo $schoolbranch->lat ?>";
var lng = "<?php echo $schoolbranch->lon ?>";

var x_pi = 3.14159265358979324 * 3000.0 / 180.0;
var lat_begin = 0;
var lon_begin = 0;

function getPositionSuccess(position)
{
	var lat_begin = position.coords.latitude;
	var lon_begin = position.coords.longitude;

    var z = Math.sqrt(lon_begin * lon_begin + lat_begin * lat_begin) + 0.00002 * Math.sin(lat_begin * x_pi);
    var theta = Math.atan2(lat_begin, lon_begin) + 0.000003 * Math.cos(lon_begin * x_pi);

    lon_begin = z * Math.cos(theta) + 0.0065;
    lat_begin = z * Math.sin(theta) + 0.006;

	initBMap(lng,lat,lon_begin,lat_begin);
	$("#direct_id").attr('href', "http://api.map.baidu.com/direction?origin=latlng:"+lat_begin+","+lon_begin+"|name:我的位置&destination=latlng:"+lat+","+lng+"|name:"+title+"&mode=driving&origin_region=武汉&destination_region=武汉&output=html&src=wosotech|wosotech");
}
	 
function getPositionError(error) 
{
	initBMap(lng,lat,lon_begin,lat_begin);
}

$(document).ready(function()
{
	//alert('ready');
	if (lon_begin == 0 || lat_begin == 0)
	{
		if (navigator.geolocation)
		{
			navigator.geolocation.getCurrentPosition(getPositionSuccess, getPositionError, {timeout:3000});
		}
		else
		{
			//alert('no geolocation');
			initBMap(lng,lat,lon_begin,lat_begin);
		}
	}
	else
	{
		initBMap(lng,lat,lon_begin,lat_begin);
	}
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

var initBMap = function(lng1,lat1,my_lng,my_lat)
{	
	var map = new BMap.Map("map_container");
	map.centerAndZoom(pointB,16);
	var pointB = new BMap.Point(lng1,lat1);
	map.centerAndZoom(pointB,16);
	map.addControl(new BMap.NavigationControl());	

	if (my_lng == 0 || my_lat == 0)
	{
		var point = new BMap.Point(lng1,lat1);
		var marker = new BMap.Marker(point);
		map.addOverlay(marker);  
		return;
	}
	var pointA = new BMap.Point(my_lng, my_lat);	//自己在的位置	
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
	body{padding: 0px;}
	#map_container{margin:0px;width:100%;height:320px;}
	#result{height:100%;width:100%;}
	.panel-body img{max-width:100%;}
</style>

</head>
<body>
<div id="map_container"></div>

<div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title"><?= $schoolbranch->title ?></h3>
        </div>
        <div class="panel-body">

          	电话：<a href="tel:<?= $schoolbranch->mobile ?>"><?= $schoolbranch->mobile ?></a>

          	<!--	
          	&nbsp;&nbsp;&nbsp;&nbsp;
			<a id="direct_id" href="<//?php echo "http://api.map.baidu.com/direction?origin=latlng:{$lat_begin},{$lon_begin}|name:我的位置&destination=latlng:{$schoolbranch->lat},{$schoolbranch->lat}|name:{$schoolbranch->addr}&mode=driving&origin_region=武汉&destination_region=武汉&output=html&src=wosotech|wosotech"; ?>">我要导航</a>
			-->

          	<br />
			地址：<?= $schoolbranch->addr ?>

		
			<!--
			<a id="direct_id" href="<?//php echo "http://api.map.baidu.com/direction?origin=latlng:0,0|name:我的位置&destination=latlng:0,0|name:武昌关山一路&mode=driving&origin_region=武汉&destination_region=武汉&output=html&src=wosotech|wosotech"; ?>">我要导航</a>
			-->
			<br/>
   	    </div>
</div>
<div id="result"></div>


</body>
</html>





