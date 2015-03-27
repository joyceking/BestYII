<?php

namespace common\models;

use Yii;

/*
#学生-课程关系
DROP TABLE IF EXISTS yss_student_course;
CREATE TABLE IF NOT EXISTS yss_student_course (
    student_course_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '学生id',
    course_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '课程id',
    status tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '课时计划的状态, 0:初始, 1:安排计划就绪, 2:各方同意(学生进入学习中), 9:计划执行结束(即学完了),当签到到完毕后自动置此标志',
    score int(10) unsigned NOT NULL DEFAULT 0 COMMENT '成绩',
    CONSTRAINT FOREIGN KEY (student_id) REFERENCES yss_student (student_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (course_id) REFERENCES yss_course (course_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

*/

class MStudentCourse extends \yii\db\ActiveRecord
{
	const STUDENT_COURSE_STATUS_NONE = 0;
	const STUDENT_COURSE_STATUS_READY = 1;
	const STUDENT_COURSE_STATUS_APPROVE = 1;
	const STUDENT_COURSE_STATUS_OVER = 9;

    public static function tableName()
    {
        return 'yss_student_course';
    }

    public function rules()
    {
        return [
            [['student_id', 'course_id', 'score', 'status'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'student_course_id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'status' => Yii::t('app', 'Status'),            
            'score' => Yii::t('app', 'Score'),
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(MStudent::className(), ['student_id' => 'student_id']);
    }

    public function getCourse()
    {
        return $this->hasOne(MCourse::className(), ['course_id' => 'course_id']);
    }

	public static function getStudentCourseStatusOptionName($key=null)
	{
		$arr = array(
			static::STUDENT_COURSE_STATUS_NONE => '已报名',
			static::STUDENT_COURSE_STATUS_READY => '课时计划已安排',
			static::STUDENT_COURSE_STATUS_OVER => '课时全部学习结束',
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}

}
