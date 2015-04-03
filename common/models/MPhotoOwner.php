<?php

namespace common\models;

use Yii;
use common\models\MPhoto;
use yii\helpers\ArrayHelper;

/*
  #相册与所有者的关系
  DROP TABLE IF EXISTS yss_photo_owner;
  CREATE TABLE IF NOT EXISTS yss_photo_owner (
  photo_owner_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  photo_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '照片id',
  owner_cat int(10) unsigned NOT NULL DEFAULT 0 COMMENT '此照片所有者类型',
  owner_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '所有者id',
  sort_order int(10) unsigned NOT NULL DEFAULT 0 COMMENT '显示序号,大号显示在前',
  create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  KEY idx_owner(owner_cat,owner_id,sort_order),
  KEY idx_photo_id(photo_id)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

  ALTER TABLE yss_photo_owner ADD KEY idx_photo_id(photo_id);
  ALTER TABLE yss_photo_owner ADD create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间' after sort_order;
 */

class MPhotoOwner extends \yii\db\ActiveRecord {

    const PHOTO_OWNER_STUDENT = 1;
    const PHOTO_OWNER_TEACHER = 2;
    const PHOTO_OWNER_SCHOOL = 3;
    const PHOTO_OWNER_SCHOOLBRANCH = 4;
    const PHOTO_OWNER_COURSE = 5;
    const PHOTO_OWNER_COURSEUNIT = 6;
    const PHOTO_OWNER_SIGNON = 7;
    const PHOTO_OWNER_ROOM = 8;
    const PHOTO_OWNER_GROUP = 9;
    const PHOTO_OWNER_OTHER = 0;

    public static function tableName() {
        return 'yss_photo_owner';
    }

    public function rules() {
        return [
            [['create_time'], 'safe'],
            [['owner_cat', 'owner_id', 'sort_order'], 'required'],
            [['owner_cat', 'owner_id', 'sort_order'], 'integer'],
            ['photo_id', 'exist', 'targetAttribute' => 'photo_id', 'targetClass' => MPhoto::className()],
            [['owner_cat', 'owner_id', 'photo_id'], 'unique', 'targetAttribute' => ['owner_cat', 'owner_id', 'photo_id']],
        ];
    }

    public function attributeLabels() {
        return [
            'photo_owner_id' => Yii::t('app', 'Photo Owner ID'),
            'photo_id' => Yii::t('app', 'Photo ID'),
            'owner_cat' => Yii::t('app', 'Owner Cat'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'create_time' => Yii::t('app', 'Create Time'),
        ];
    }

    public function init() {
        $this->sort_order = 0;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->create_time = date("Y-m-d H:i:s");
            return true;
        } else {
            return false;
        }
    }

    public function getPhoto() {
        return $this->hasOne(MPhoto::className(), ['photo_id' => 'photo_id']);
    }

    public static function getPhotoOwnerCatOptionName($key = null) {
        $arr = array(
            self::PHOTO_OWNER_STUDENT => Yii::t('backend', 'Student'),
            self::PHOTO_OWNER_TEACHER => Yii::t('backend', 'Teacher'),
            self::PHOTO_OWNER_SCHOOL => Yii::t('backend', 'School'),
            self::PHOTO_OWNER_SCHOOLBRANCH => Yii::t('backend', 'SchoolBranch'),
            self::PHOTO_OWNER_COURSE => Yii::t('backend', 'Course'),
            self::PHOTO_OWNER_COURSEUNIT => Yii::t('backend', 'CourseUnit'),
            self::PHOTO_OWNER_SIGNON => Yii::t('backend', 'CourseScheduleSignon'),
            self::PHOTO_OWNER_ROOM => Yii::t('backend', 'Room'),
            self::PHOTO_OWNER_GROUP => Yii::t('backend', 'Group'),
            self::PHOTO_OWNER_OTHER => Yii::t('backend', 'Other'),
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getPhotosBySignon($owner_cat, $owner_id, $limit = 10) {
        $models = MPhotoOwner::find()->where(['owner_cat' => $owner_cat, 'owner_id' => $owner_id])->orderBy(['sort_order' => SORT_DESC])->limit($limit)->all();
        $photos = [];
        foreach ($models as $model) {
            if (!empty($model->photo)) {
                $photos[] = $model; //->photo;
                //var_dump($model->photoBySignon);
            }
        }
        return $photos;
    }

    public static function getPhotosByOwner($owner_cat, $owner_id, $limit = 10) {
        $models = MPhotoOwner::find()->where(['owner_cat' => $owner_cat, 'owner_id' => $owner_id])->orderBy(['sort_order' => SORT_DESC])->limit($limit)->all();
        $photos = [];
        foreach ($models as $model) {
            if (!empty($model->photo)) {
                $photos[] = $model->photo;
            }
        }
        return $photos;
    }

    public function getPhotoBySignon() {
        return $this->hasOne(MCourseScheduleSignon::className(), [ 'student_id' => 'owner_id']);
    }

    public static function getPhotoByOwner($owner_cat, $owner_id) {
        $model = MPhotoOwner::find()->where(['owner_cat' => $owner_cat, 'owner_id' => $owner_id])->orderBy(['sort_order' => SORT_DESC])->one();
        return $model->photo;
    }

    public static function getSqlByOwnerAndTags($owner_cat, $owner_id, $tags = false, $limit = false) {
        if ($tags === false && $limit === false) {
            $sql = <<<EOD
			SELECT t1.photo_id, t1.owner_cat, t1.owner_id, t1.sort_order, t1.photo_owner_id, t1.create_time, t2.tags from yss_photo_owner t1 
			INNER JOIN yss_photo t2 on t1.photo_id = t2.photo_id
			WHERE t1.owner_cat = '$owner_cat' AND t1.owner_id = '$owner_id'
			ORDER BY t1.sort_order DESC, t1.create_time DESC
EOD;
        }

        if ($tags === false && $limit !== false) {
            $sql = <<<EOD
			SELECT t1.photo_id, t1.owner_cat, t1.owner_id, t1.sort_order, t1.photo_owner_id, t1.create_time, t2.tags from yss_photo_owner t1 
			INNER JOIN yss_photo t2 on t1.photo_id = t2.photo_id
			WHERE t1.owner_cat = '$owner_cat' AND t1.owner_id = '$owner_id'
			ORDER BY t1.sort_order DESC, t1.create_time DESC 
			LIMIT $limit		
EOD;
        }

        if ($tags !== false && $limit === false) {
            $sql = <<<EOD
			SELECT t1.photo_id, t1.owner_cat, t1.owner_id, t1.sort_order, t1.photo_owner_id, t1.create_time, t2.tags from yss_photo_owner t1 
			INNER JOIN yss_photo t2 on t1.photo_id = t2.photo_id
			WHERE t1.owner_cat = '$owner_cat' AND t1.owner_id = '$owner_id' AND t2.tags = '$tags'
			ORDER BY t1.sort_order DESC, t1.create_time DESC 
EOD;
        }

        if ($tags !== false && $limit !== false) {
            $sql = <<<EOD
			SELECT t1.photo_id, t1.owner_cat, t1.owner_id, t1.sort_order, t1.photo_owner_id, t1.create_time, t2.tags from yss_photo_owner t1 
			INNER JOIN yss_photo t2 on t1.photo_id = t2.photo_id
			WHERE t1.owner_cat = '$owner_cat' AND t1.owner_id = '$owner_id' AND t2.tags = '$tags'
			ORDER BY t1.sort_order DESC, t1.create_time DESC 
			LIMIT $limit		
EOD;
        }
        return $sql;
    }

    public static function getPhotoOwnersByOwnerAndTags($owner_cat, $owner_id, $tags = false, $limit = false) {
        $sql = static::getSqlByOwnerAndTags($owner_cat, $owner_id, $tags, $limit);
        $rows = Yii::$app->db->createCommand($sql)->queryAll();
        return $rows;
    }

    public static function getPhotoOwnerIdsByOwnerAndTags($owner_cat, $owner_id, $tags = false, $limit = false) {
        $rows = static::getPhotoOwnersByOwnerAndTags($owner_cat, $owner_id, $tags, $limit);
        return ArrayHelper::getColumn($rows, 'photo_owner_id');
    }

    public static function getPhotoIdsByOwnerAndTags($owner_cat, $owner_id, $tags = false, $limit = false) {
        $rows = static::getPhotoOwnersByOwnerAndTags($owner_cat, $owner_id, $tags, $limit);
        return ArrayHelper::getColumn($rows, 'photo_id');
    }

}
