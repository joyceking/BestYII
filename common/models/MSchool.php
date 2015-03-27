<?php

namespace common\models;

use Yii;
//use vova07\fileapi\behaviors\UploadBehavior;
use backend\behaviors\UploadBehavior;
use backend\modules\wx\models\MGh;
use backend\behaviors\GetPhotoBehavior;

/*
#学校
DROP TABLE IF EXISTS yss_school;
CREATE TABLE IF NOT EXISTS yss_school (
    school_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    gh_id VARCHAR(32) NOT NULL DEFAULT '',    
    title VARCHAR(256) NOT NULL DEFAULT '' COMMENT '学校名称',
    slogan VARCHAR(256) NOT NULL DEFAULT '' COMMENT '口号',    
    logo_url VARCHAR(256) NOT NULL DEFAULT '',
    des TEXT NOT NULL DEFAULT '' COMMENT '学校描述',
	history TEXT,
    addr VARCHAR(256) NOT NULL DEFAULT '',
    mobile VARCHAR(64) NOT NULL DEFAULT '',
    website VARCHAR(256) NOT NULL DEFAULT '网址',
    create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    update_time TIMESTAMP NOT NULL DEFAULT 0 COMMENT '最后修改时间',
    is_delete tinyint(3) unsigned NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;

ALTER TABLE yss_school ADD history TEXT after des;
ALTER TABLE yss_school ADD gh_id VARCHAR(32) NOT NULL DEFAULT '' after school_id;


*/

/*
DROP DATABASE IF EXISTS yss;
CREATE DATABASE yss DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci; 

ALTER TABLE article ENGINE=MyISAM;
ALTER TABLE article_category ENGINE=MyISAM;
ALTER TABLE file_storage_item ENGINE=MyISAM;
ALTER TABLE i18n_message ENGINE=MyISAM;
ALTER TABLE i18n_source_message ENGINE=MyISAM;
ALTER TABLE key_storage_item ENGINE=MyISAM;
ALTER TABLE page ENGINE=MyISAM;
ALTER TABLE rbac_auth_assignment ENGINE=MyISAM;
ALTER TABLE rbac_auth_item ENGINE=MyISAM;
ALTER TABLE rbac_auth_item_child ENGINE=MyISAM;
ALTER TABLE rbac_auth_rule ENGINE=MyISAM;
ALTER TABLE system_event ENGINE=MyISAM;
ALTER TABLE system_log ENGINE=MyISAM;
ALTER TABLE system_migration ENGINE=MyISAM;
ALTER TABLE user ENGINE=MyISAM;
ALTER TABLE user_profile ENGINE=MyISAM;
ALTER TABLE widget_carousel ENGINE=MyISAM;
ALTER TABLE widget_carousel_item ENGINE=MyISAM;
ALTER TABLE widget_menu ENGINE=MyISAM;
ALTER TABLE widget_text ENGINE=MyISAM;

cd c:\htdocs\yss>
php console/yii migrate
php console/yii rbac/init
*/

class MSchool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_school';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['des','title'], 'required'],
            [['des', 'title', 'history', 'gh_id'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete'], 'integer'],
            [['title', 'slogan', 'logo_url', 'addr', 'website'], 'string', 'max' => 256],
            [['mobile'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'school_id' => Yii::t('app', 'School ID'),
            'title' => Yii::t('app', 'Title'),
            'slogan' => Yii::t('app', 'Slogan'),
            'logo_url' => Yii::t('app', 'Logo Url'),
            'des' => Yii::t('app', 'Des'),
            'history' => Yii::t('app', 'History'),            
            'addr' => Yii::t('app', 'Addr'),
            'mobile' => Yii::t('app', 'Mobile'),
            'website' => Yii::t('app', 'Website'),
            'create_time' => Yii::t('app', 'Create Time'),
            'update_time' => Yii::t('app', 'Update Time'),
            'gh_id' => Yii::t('app', 'Gh ID'),            
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolBranches()
    {
        return $this->hasMany(MSchoolBranch::className(), ['school_id' => 'school_id']);
    }

	public function saveSchoolBranch($model)
	{
		$model->school_id = $this->school_id;
		return $model->save();
	}

    public function getGh()
    {
        return $this->hasOne(MGh::className(), ['gh_id' => 'gh_id']);
    }
	
	public function getStudents()
	{
		return $this->hasMany(MStudent::className(), ['school_id' => 'school_id']);
	}

    public function getCourses()
    {
        return $this->hasMany(MCourse::className(), ['school_id' => 'school_id']);
    }

	public function saveStudent($model)
	{
		$model->school_id = $this->school_id;
		return $model->save();
	}

	public function getTeachers()
	{
		return $this->hasMany(MTeacher::className(), ['school_id' => 'school_id']);
	}

	public function saveTeacher($model)
	{
		$model->school_id = $this->school_id;
		return $model->save();
	}
	
	public static function getSexOptionName($key=null)
	{
		$arr = array(
			'M' => '男',
			'F' => '女',
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}

	public static function getNationalityOptionName($key=null)
	{
		$arr = array(
			'China' => '中国',
			'USA' => '美国',
			'Other' => '其它',
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}

    public static function getRegionOptionName($key=null)
    {
        $arr = array(
            '江岸区' => '江岸区',
            '江汉区' => '江汉区',
            '硚口区' => '硚口区',
            '汉阳区' => '汉阳区',
            '武昌区' => '武昌区',
            '洪山区' => '洪山区',
            '青山区' => '青山区',
            '蔡甸区' => '蔡甸区',
            '汉南区' => '汉南区',
            '江夏区' => '江夏区',
            '黄陂区' => '黄陂区',
            '新洲区' => '新洲区',
            '东西湖区' => '东西湖区',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getYesNoOptionName($key=null)
	{
		$arr = array(
			'0' => Yii::t('backend', 'No'),
			'1' => Yii::t('backend', 'Yes'),
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}

	public static function getSchoolIdFromSession()
	{
		return empty(Yii::$app->params['school_id_idealangel']) ? 10 : Yii::$app->params['school_id_idealangel'];
	}

	public function getLogoUrl()
	{
		return Yii::getAlias('@storageUrl').'/logo/'.$this->logo_url;
	}

	public function behaviors()
	{
		return [
			'uploadBehavior' => [
				'class' => UploadBehavior::className(),
				'attributes' => [
					'logo_url' => [
						'tempPath' => '@backend/runtime/tmp',
						'path' => '@storage/logo',
						'url' => Yii::getAlias('@storageUrl').'/logo',
					]
				]
			],
			
			'GetPhotoBehavior' => [
				'class' => GetPhotoBehavior::className(),
			]
			
		];
	}

	public function getOwnerCat()
	{
		return MPhotoOwner::PHOTO_OWNER_SCHOOL;
	}
/*
	public function getPhotosCount($tags = false, $limit = false)
	{
		$photoIds = MPhotoOwner::getPhotoIdsByOwnerAndTags($this->ownerCat, $this->primaryKey, $tags, $limit);
		return count($photoIds);
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

/*

*/
