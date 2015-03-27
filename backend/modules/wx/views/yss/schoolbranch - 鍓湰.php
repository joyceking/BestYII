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


</head>

<style type="text/css">
  body{padding: 10px;}
</style>

<body>

  <?php
	 use backend\modules\wx\models\U;
    use common\models\MSchool;
    use common\models\MSchoolBranch;
    use common\models\MTeacher;
    use common\models\MPhoto;
    use common\models\MPhotoOwner;
    use common\models\MMapApi;

    foreach($schoolbranchs as $schoolbranch)
    {
      $photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_SCHOOLBRANCH, $schoolbranch->school_branch_id, 1);
	  if (empty($photos))
		 break; 
      $photo = $photos[0];
    ?>
      <div class="media">
   
          <img src="<?php echo Yii::getAlias('@storageUrl').$photo->pic_url; ?>" alt="" width="100%">

          <div class="media-body">
            <h4 class="media-heading"><?= $schoolbranch->title ?></h4>
            <p><?= $schoolbranch->addr ?></p>
            
          </div>

          <p> <a href="http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranchnav&school_branch_id=<?php echo $schoolbranch->school_branch_id;?>" class="btn btn-success btn-block" role="button">我要导航</a></p>
      </div>
      <hr>
  <?php
    }
  ?>

</body>
</html>





