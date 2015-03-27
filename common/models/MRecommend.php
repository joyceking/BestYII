<?php
/*

DROP TABLE IF EXISTS yss_recommend;
CREATE TABLE IF NOT EXISTS `yss_recommend` (
  `recommend_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gh_id` VARCHAR(32) NOT NULL DEFAULT '',
  `openid` VARCHAR(32) NOT NULL DEFAULT '',
  `name` varchar(16) NOT NULL DEFAULT '' COMMENT '被推荐者姓名',
  `mobile` varchar(64) NOT NULL DEFAULT '' COMMENT '被推荐者联系电话',
  `status` tinyint(3) NOT NULL DEFAULT 0 COMMENT '推荐状态',
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`recommend_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;


*/

namespace common\models;

use Yii;

use backend\modules\wx\models\MUser;

/**
 * This is the model class for table "yss_recommend".
 *
 * @property string $recommend_id
 * @property string $gh_id
 * @property string $openid
 * @property string $name
 * @property string $mobile
 * @property string $status
 * @property string $create_time
 */
class MRecommend extends \yii\db\ActiveRecord
{
	const RECOMMEND_STATUS_NONE = 0;
	const RECOMMEND_STATUS_FOLLOW = 1;
	const RECOMMEND_STATUS_TRY = 2;
	const RECOMMEND_STATUS_ERR = 8;
	const RECOMMEND_STATUS_OK = 9;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_recommend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['gh_id', 'openid'], 'string', 'max' => 32],
            [['name'], 'string','min' => 2, 'max' => 16],
            ['mobile', 'match', 'pattern' => '/(^(1)\d{10}$)/'],
            [['mobile', 'status'], 'string', 'max' => 64]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recommend_id' => Yii::t('backend', 'Recommend ID'),
            'gh_id' => Yii::t('backend', 'Gh ID'),
            'openid' => Yii::t('backend', 'Openid'),
            'name' => Yii::t('backend', 'Name'),
            'mobile' => Yii::t('backend', 'Mobile'),
            'status' => Yii::t('backend', 'Status'),
            'create_time' => Yii::t('backend', 'Create Time'),
        ];
    }
 
    public function getRefereesName()
    {
        return $this->hasOne(MUser::className(), ['gh_id' => 'gh_id','openid' => 'openid']);
    }

	public static function getRecommendStatusOptionName($key=null)
	{
		$arr = array(
			self::RECOMMEND_STATUS_NONE => '已推荐',
			self::RECOMMEND_STATUS_FOLLOW => '跟进中',
			self::RECOMMEND_STATUS_TRY => '已试听',
			self::RECOMMEND_STATUS_ERR => '推荐失败',
			self::RECOMMEND_STATUS_OK => '推荐成功',
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}
}
