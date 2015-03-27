<?php

namespace common\models;

use Yii;

/*
#课时
DROP TABLE IF EXISTS yss_course_unit;
CREATE TABLE IF NOT EXISTS yss_course_unit (
    course_unit_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    course_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '课程id',
    title VARCHAR(256) NOT NULL DEFAULT '' COMMENT '该课时名称',
    des TEXT NOT NULL DEFAULT '' COMMENT '该课时介绍',
    prepare TEXT NOT NULL DEFAULT '' COMMENT '上课前准备工作',
    caution TEXT NOT NULL DEFAULT '' COMMENT '注意事项',
    minutes int(10) unsigned NOT NULL DEFAULT 0 COMMENT '时长(分钟)',
    sort_order int(10) unsigned NOT NULL DEFAULT 0 COMMENT '课时序号,小号在前',
    CONSTRAINT FOREIGN KEY (course_id) REFERENCES yss_course (course_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
*/

class MCourseUnit extends \yii\db\ActiveRecord
{
    public function init()
    {
    }
	
    public static function tableName()
    {
        return 'yss_course_unit';
    }

    public function rules()
    {
        return [
            [['course_id', 'minutes', 'sort_order'], 'integer'],
            [['title'], 'required'],
            [['des', 'prepare', 'caution'], 'string'],
            [['title'], 'string', 'max' => 256]
        ];
    }

    public function attributeLabels()
    {
        return [
            'course_unit_id' => Yii::t('app', 'Course Unit ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'title' => Yii::t('app', 'Title'),
            'des' => Yii::t('app', 'Des'),
            'prepare' => Yii::t('app', 'Prepare'),
            'caution' => Yii::t('app', 'Caution'),
            'minutes' => Yii::t('app', 'Minutes'),
            'sort_order' => Yii::t('app', 'Sort Order'),
        ];
    }

    public function getMCourseSchedules()
    {
        return $this->hasMany(MCourseSchedule::className(), ['course_unit_id' => 'course_unit_id']);
    }

    public function getCourse()
    {
        return $this->hasOne(MCourse::className(), ['course_id' => 'course_id']);
    }

    public function getCourseUnitFullInfo()
    {
		return $this->title. ' ['. $this->course_unit_id . ']';
    }

    public function getGroups()
    {
        return $this->hasMany(MGroup::className(), ['course_unit_id' => 'course_unit_id']);
    }

    public function getSubcourseCourseUnits()
    {
        return $this->hasMany(MSubcourseCourseUnit::className(), ['course_unit_id' => 'course_unit_id']);
    }

    // calculate how many students have already applied this unit
    public function getAppliedCourseUnitStudents()
    {
        $students = [];
        foreach($this->subcourseCourseUnits as $subcourseCourseUnit) {
            $subcourse = $subcourseCourseUnit->subcourse;
            foreach($subcourse->studentSubcourses as $studentSubcourse) {
                $students[] = $studentSubcourse->student;
            }
        }
        return $students;
    }

}
