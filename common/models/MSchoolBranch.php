<?php

namespace common\models;

use Yii;
use backend\behaviors\GetPhotoBehavior;

/*
#校区
DROP TABLE IF EXISTS yss_school_branch;
CREATE TABLE IF NOT EXISTS yss_school_branch (
    school_branch_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    school_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '学校id',
    title VARCHAR(256) NOT NULL DEFAULT '' COMMENT '分校名称',
    region VARCHAR(32) NOT NULL DEFAULT '' COMMENT '区域',
    des TEXT NOT NULL DEFAULT '' COMMENT '分校描述',
    addr VARCHAR(256) NOT NULL DEFAULT '',
    mobile VARCHAR(64) NOT NULL DEFAULT '',
    lat float(10,6) NOT NULL DEFAULT '0.000000',
    lon float(10,6) NOT NULL DEFAULT '0.000000',
    create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    update_time TIMESTAMP NOT NULL DEFAULT 0 COMMENT '最后修改时间',
    is_delete tinyint(3) unsigned NOT NULL DEFAULT 0,
    CONSTRAINT FOREIGN KEY (school_id) REFERENCES yss_school (school_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=100;


ALTER TABLE yss_school_branch ADD lat float(10,6) NOT NULL DEFAULT '0.000000';
ALTER TABLE yss_school_branch ADD lon float(10,6) NOT NULL DEFAULT '0.000000';

ALTER TABLE `yss_school_branch` ADD `region` CHAR(32) NULL DEFAULT NULL COMMENT '行政区域' AFTER `school_id`;
*/




class MSchoolBranch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_school_branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'is_delete'], 'integer'],
            [['des'], 'required'],
            [['des'], 'string'],
            [['region'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['title', 'addr'], 'string', 'max' => 256],
            [['lat','lon'], 'number'],
            [['mobile'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'school_branch_id' => Yii::t('app', 'School Branch ID'),
            'school_id' => Yii::t('app', 'School ID'),
            'title' => Yii::t('app', 'Title'),
            'des' => Yii::t('app', 'Des'),
            'lat' => Yii::t('app', 'Latitude'),
            'lon' => Yii::t('app', 'Longtitude'),                        
            'addr' => Yii::t('app', 'Addr'),
            'mobile' => Yii::t('app', 'Mobile'),
            'create_time' => Yii::t('app', 'Create Time'),
            'update_time' => Yii::t('app', 'Update Time'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'region' => Yii::t('app', 'region'),
        ];
    }

	public function behaviors()
	{
		return [
			'GetPhotoBehavior' => [
				'class' => GetPhotoBehavior::className(),
			]
			
		];
	}

    public function getRooms()
    {
        return $this->hasMany(MRoom::className(), ['school_branch_id' => 'school_branch_id']);
    }

    public function getSchool()
    {
        return $this->hasOne(MSchool::className(), ['school_id' => 'school_id']);
    }

	public function saveRoom($model)
	{
		$model->school_branch_id = $this->school_branch_id;
		return $model->save();
	}


    public static function getNearestSchoolBranch($gh_id, $lon, $lat)
    {
        $key = __METHOD__."{$gh_id}_{$lon}_{$lat}";
        $value = Yii::$app->cache->get($key);
        if ($value !== false)
            return $value;
        $map = new MMapApi;
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $rows = MSchoolBranch::find()->where(['school_id' => $school->school_id])->asArray()->all();
        foreach($rows as $key => &$row)
        {
            if ($row['lon'] < 1)
            {
                unset($rows[$key]);
                continue;
            }
            $row['distance'] = $map->getDistance($lon, $lat, $row['lon'], $row['lat']);
        }        
        unset($row);
      //U::W($rows);    
        \yii\helpers\ArrayHelper::multisort($rows, 'distance');
        //U::W($rows);    
        Yii::$app->cache->set($key, $rows, YII_DEBUG ? 10 : 5*60);
        return $rows;
    }

	public function getOwnerCat()
	{
		return MPhotoOwner::PHOTO_OWNER_SCHOOLBRANCH;
	}
	
/*
	public function getPhotosCount($tags = false, $limit = false)
	{
		$params = [':owner_cat' => $this->ownerCat, ':owner_id' => $this->primaryKey];
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
		$photoIds = MPhotoOwner::getPhotoIdsByOwnerAndTags($this->ownerCat, $this->primaryKey, $tags, $limit);
		return MPhoto::findAll($photoIds);
	}

	public function getPhoto($tags = false)
	{
		$photoIds = MPhotoOwner::getPhotoIdsByOwnerAndTags($this->ownerCat, $this->primaryKey, $tags, 1);
		if (empty($photoIds))
			return null;
		return MPhoto::findOne($photoIds[0]);
	}
*/
}
