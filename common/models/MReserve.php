<?php
/*
DROP TABLE IF EXISTS yss_reserve;
CREATE TABLE IF NOT EXISTS `yss_reserve` (
  `reserve_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gh_id` VARCHAR(32) NOT NULL DEFAULT '',
  `openid` VARCHAR(32) NOT NULL DEFAULT '',
  `school_branch_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学校id',
  `course_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '课程id',
  `name` varchar(16) NOT NULL DEFAULT '' COMMENT '姓名',
  `sex` char(1) NOT NULL DEFAULT '' COMMENT '姓别F/M',
  `age` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
  `mobile` varchar(64) NOT NULL DEFAULT '' COMMENT '联系电话',
  `memo` varchar(256) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(64) NOT NULL DEFAULT '预约中' COMMENT '预约状态回复',
  PRIMARY KEY (`reserve_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

*/
namespace common\models;

use Yii;

/**
 * This is the model class for table "yss_reserve".
 *
 * @property string $reserve_id
 * @property string $school_branch_id
 * @property string $course_id
 * @property string $name
 * @property string $sex
 * @property string $age
 * @property string $mobile
 * @property string $memo
 */
class MReserve extends \yii\db\ActiveRecord
{

    const RESERVE_STATUS_NONE = 0;
    const RESERVE_STATUS_OK = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_reserve';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_branch_id', 'course_id', 'age'], 'integer'],
            [['name'], 'string', 'max' => 16],
            [['sex'], 'string', 'max' => 1],
            [['mobile'], 'string', 'max' => 64],
            [['mobile'], 'string', 'max' => 64],
            [['status'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reserve_id' => Yii::t('backend', 'Reserve ID'),
            'school_branch_id' => Yii::t('backend', 'School Branch ID'),
            'course_id' => Yii::t('backend', 'Course ID'),
            'name' => Yii::t('backend', 'Name'),
            'sex' => Yii::t('backend', 'Sex'),
            'age' => Yii::t('backend', 'Age'),
            'mobile' => Yii::t('backend', 'Mobile'),
            'memo' => Yii::t('backend', 'Memo'),
            'status' => Yii::t('backend', 'Status'),
        ];
    }

    public function getCourse()
    {
        return $this->hasOne(MCourse::className(), ['course_id' => 'course_id']);
    }

    public function getSchoolBranch()
    {
        return $this->hasOne(MSchoolBranch::className(), ['school_branch_id' => 'school_branch_id']);
    }
      
    public static function getSexOptionName($key=null)
    {
        $arr = array(
            'M' => '男',
            'F' => '女',
        );        
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getReserveStatusOptionName($key=null)
    {
        $arr = array(
            self::RESERVE_STATUS_NONE => '预约中',
            self::RESERVE_STATUS_OK => '预约成功',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

}
