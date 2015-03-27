<?php

namespace backend\behaviors;

use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\validators\Validator;
use Yii;

/*
图片TAG(标签)  用途                 参考文件
-----------------------------------------------------
视频          课程介绍中视频         backend\modules\wx\views\yss\courseshowdetail.php
团队风采      用在团队风采照片组中   backend\modules\wx\views\yss\adabout.php
校区设施      用在展示校区照片组中   backend\modules\wx\views\yss\schoolbranchshowdetail.php
*/


use common\models\MPhotoOwner;
use common\models\MPhoto;

class GetPhotoBehavior extends Behavior
{
	public function getPhotosCount($tags = false, $limit = false)
	{
		$params = [':owner_cat' => $this->owner->ownerCat, ':owner_id' => $this->owner->primaryKey];
		$sql = <<<EOD
		SELECT COUNT(*) from yss_photo_owner t1 
		INNER JOIN yss_photo t2 on t1.photo_id = t2.photo_id
		WHERE t1.owner_cat = :owner_cat AND t1.owner_id = :owner_id
EOD;
		$count = Yii::$app->db->createCommand($sql, $params)->queryScalar();		
		return $count;	
	}

	public function getPhotos($tags = false, $limit = false)
	{
		$photoIds = MPhotoOwner::getPhotoIdsByOwnerAndTags($this->owner->ownerCat, $this->owner->primaryKey, $tags, $limit);
		return MPhoto::findAll($photoIds);
	}

	public function getPhoto($tags = false)
	{
		$photoIds = MPhotoOwner::getPhotoIdsByOwnerAndTags($this->owner->ownerCat, $this->owner->primaryKey, $tags, 1);
		if (empty($photoIds))
			return null;
		return MPhoto::findOne($photoIds[0]);
	}

}
