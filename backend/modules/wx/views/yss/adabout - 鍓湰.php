<?php
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\models\MPhoto;

$this->registerJsFile(Yii::$app->getRequest()->baseUrl.'/js/wechat.js?v0.1');
$this->title = "关于爱迪"
?>

<style type="text/css">
	p{font-size: 12pt;}
</style>

<!--
<img src='./swiper/head1.jpg' width='100%' class="img-rounded">
-->
<img src='<?php echo MPhoto::getUploadPicUrl('logo.jpg') ?>' width='100%' class="img-rounded">

<br><br>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">

		<p>
		爱迪天才宝贝学习中心为武汉爱迪夏恩少儿培训学校旗下一所儿童思维启发教育机构品牌。
		</p>
		<!--
		<p>
		爱迪天才宝贝学习中心是武汉第一家引进国外先进教育课程体系教育机构，课程特别为2岁至12岁的孩子量身定制。经过几年的稳步发展，课程涉及到由艺术、语言、科学、运动，自然探索以及数学等多方面，成为了多学科、跨学科指导儿童身心健康及思维创意发展的综合性教育机构。
		</p>

		<p>
		爱迪天才宝贝学习中心植根于中国文化，立足本土、放眼国际，吸收借鉴国际先进的教育理念，培养健康、快乐、有创造力和竞争力的儿童。目前，爱迪天才已接受并培养近万名儿童，从业人数达到百余人，累计为近万家庭提供了高品质的儿童思维启发与教育服务，每周有100多个家庭走进爱迪天才。
		</p>
		-->

		<br>
		
		<!--
		<img src="<?//php echo Yii::getAlias('@storageUrl').$photo->pic_url; ?>" width="100%" class="img-rounded">
		-->
	</div>
</div>
<br>
<?php
