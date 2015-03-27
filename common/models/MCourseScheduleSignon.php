<?php

namespace common\models;

use Yii;

use common\models\MSchool;
/*
#课时计划学生签到表
DROP TABLE IF EXISTS yss_course_schedule_signon;
CREATE TABLE IF NOT EXISTS yss_course_schedule_signon (
    signon_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    course_schedule_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '课时计划ID',
    student_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '签到的学生ID',
    is_signon tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0:未签到, 1:签到',
    is_repay tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '补课标志',
    score tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '教师对小孩的打分',
    memo VARCHAR(512) NOT NULL DEFAULT '',
    create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	UNIQUE KEY idx_course_schedule_id_student_id(course_schedule_id, student_id),
    CONSTRAINT FOREIGN KEY (student_id) REFERENCES yss_student (student_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
*/

class MCourseScheduleSignon extends \yii\db\ActiveRecord
{
    const SIGNON_STATUS_NONE = 0;
    const SIGNON_STATUS_YES = 1;
    const SIGNON_STATUS_NO = 2;

    public static function tableName()
    {
        return 'yss_course_schedule_signon';
    }

    public function rules()
    {
        return [
            [['course_schedule_id', 'student_id', 'is_signon', 'is_repay', 'score'], 'integer'],
            [['memo'], 'string', 'max' => 512],
            [['create_time'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'signon_id' => Yii::t('app', 'Signon ID'),
            'course_schedule_id' => Yii::t('app', 'Course Schedule ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'is_signon' => Yii::t('app', 'Is Signon'),
            'is_repay' => Yii::t('app', 'Is Repay'),
            'score' => Yii::t('app', 'Score'),
            'memo' => Yii::t('app', 'Comment'),            
            'create_time' => Yii::t('app', 'Create Time'),
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(MStudent::className(), ['student_id' => 'student_id']);
    }

    public function getCourseSchedule()
    {
        return $this->hasOne(MCourseSchedule::className(), ['course_schedule_id' => 'course_schedule_id']);
    }

	public function getCourseScheduleInfo()
	{
		return $this->courseSchedule->courseUnitInfo;
	}

	public static function getSignonStatusOptionName($key=null)
	{
		$arr = array(
			self::SIGNON_STATUS_NONE => Yii::t('backend', 'Signon None'),
			self::SIGNON_STATUS_YES => Yii::t('backend', 'Signon Yes'),
			self::SIGNON_STATUS_NO => Yii::t('backend', 'Signon No'),
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}

	public function getSignonStatusName()
	{
		return static::getSignonStatusOptionName($this->is_signon);
	}

	public function getRepayStatusName()
	{
		return MSchool::getYesNoOptionName($this->is_repay);
	}

}
