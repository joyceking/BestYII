<?php

namespace common\models;

use Yii;
use backend\modules\wx\models\MGh;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\U;

/*
#openid与实体的对应关系
DROP TABLE IF EXISTS yss_openid_owner;
CREATE TABLE IF NOT EXISTS yss_openid_owner (
    openid_owner_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    gh_id VARCHAR(32) NOT NULL DEFAULT '',
    openid VARCHAR(32) NOT NULL DEFAULT '',
    owner_cat int(10) unsigned NOT NULL DEFAULT 0 COMMENT '实体类型',
    owner_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '实体id',
    UNIQUE KEY idx_openid(gh_id, openid, owner_cat, owner_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

*/

class MOpenidOwner extends \yii\db\ActiveRecord
{
    const OPENID_OWNER_STUDENT = 1;
    const OPENID_OWNER_TEACHER = 2;	

	public $owner_name;
	public $mobile;	

    public static function tableName()
    {
        return 'yss_openid_owner';
    }

    public function rules()
    {
        return [
            [['owner_cat', 'owner_id'], 'integer'],
            [['mobile'], 'string', 'max' => 16],            
            [['mobile'], 'match', 'pattern' => '/^((\+86)|(86))?1\d{10}$/'],            
          //[['mobile', 'owner_id'], 'validateMobileAndId'],                        
			['owner_name', 'required'],
            [['owner_name'], 'string', 'max' => 10]
        ];
    }

    public function validateMobileAndId()
    {
        if (empty($this->mobile) && empty($this->owner_id)) {
            $this->addError('mobile', Yii::t('backend', 'Mobile and ID can not be empty at the same time.'));
            $this->addError('owner_id', Yii::t('backend', 'Mobile and ID can not be empty at the same time.'));				
        }
    }

    public function attributeLabels()
    {
        return [
            'openid_owner_id' => Yii::t('app', 'Openid Owner ID'),
            'gh_id' => Yii::t('app', 'Gh ID'),            
            'openid' => Yii::t('app', 'Openid'),
            'owner_cat' => Yii::t('app', 'Cat'),
            'owner_id' => Yii::t('app', 'ID'),
            'owner_name' => Yii::t('app', 'Name'),
            'mobile' => Yii::t('app', 'Mobile'),            
        ];
    }

    public function getUser()
    {
        return $this->hasOne(MUser::className(), ['gh_id'=>'gh_id', 'openid' => 'openid']);
    }

	public static function getOpenidOwnerCatOptionName($key=null)
	{
		$arr = array(
			self::OPENID_OWNER_STUDENT => Yii::t('backend', 'Student'),
            self::OPENID_OWNER_TEACHER => Yii::t('backend', 'Teacher'),
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}

}
