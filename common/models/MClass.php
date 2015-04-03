<?php

namespace common\models;

use Yii;
use backend\modules\wx\models\U;
use backend\behaviors\GetPhotoBehavior;
use common\models\MGroup;

/*
  #班(学生集合), 报了相同课程的学生才能组成一个班, 一个学生如报多门课程则属于多个班
  #假设一个班有3名学生, 此班的课程有8个课时;那么在给班做计划时，要增加8条记录到schedule表中, 要加3*8条记录到signon表中
  DROP TABLE IF EXISTS yss_group;
  CREATE TABLE IF NOT EXISTS yss_group (
  group_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  school_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '学校id',
  title VARCHAR(256) NOT NULL DEFAULT '' COMMENT '班名称, 要对班上的课时做计划',
  course_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '此班所绑定的课程',
  status tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0:初始态, 1:计划已制定, 9:结束',
  create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;

  #新的设计，group建在课时上
  DROP TABLE IF EXISTS yss_group;
  CREATE TABLE IF NOT EXISTS yss_group (
  group_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  school_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '学校id',
  title VARCHAR(256) NOT NULL DEFAULT '' COMMENT '班名称, 要对班上的课时做计划',
  course_unit_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '此班所绑定的课程ID，班是建立在课程ID上的',
  status tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0:初始态, 1:计划已制定, 9:结束',
  create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  KEY idx_course_unit_id(course_unit_id),
  KEY idx_school_id(school_id)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;
 */

class MClass extends MGroup {

    public function init() {
        $this->startTime = date("Y-m-d H:i:s");
    }

    public static function tableName() {
        return 'yss_group';
    }

    public function getSubCourseUnit() {
        return $this->hasOne(MSubcourseCourseUnit::className(), ['subcourse_id' => 'course_unit_id']);
    }

    public function getSubCourse() {
        return $this->subCourseUnit->subcourse;
    }

    public function getStudentSubCourseUint() {
        return $this->groupStudents->student;
    }

    /*
     * 创建班级课程计划
     */

    public function startSchedule($startTime = '', $teacher_id = 0) {
        $this->createCourseSchedules($startTime, $teacher_id);
    }

    public function createCourseSchedules($startTime, $teacher_id = 0) {
        if (empty($startTime)) {
            $insertStudent = $this->getFirstStartTimeFromSchedule();
            $startTime = $insertStudent['start_time'];
            $teacher_id = $insertStudent['teacher_id'];
            $startCourseId = $insertStudent['course_unit_id'];
            $startTime = date("Y-m-d H:i:s");
        }

        $courseUnits = $this->subcourse->subcourseCourseUnits;

        foreach ($courseUnits as $courseUnit) {
            $schedule = MCourseSchedule::findOne(['group_id' => $this->group_id, 'course_unit_id' => $courseUnit->course_unit_id]);
            if ($schedule === null) {
                $schedule = new MCourseSchedule;
                $schedule->group_id = $this->group_id;
                $schedule->course_unit_id = $courseUnit->course_unit_id;
                $schedule->teacher_id = $teacher_id;
                //$schedule->room_id = 0;
                $schedule->start_time = $startTime;
                $startTime = date("Y-m-d H:i:s", strtotime("+1 week", strtotime($startTime)));
                $schedule->save(false);
            }
            //create signons
        }
        $courseScheduleSignons = $schedule->createStudentScheduleSignons();
    }

    public function getFirstStartTimeFromSchedule() {
        $courseUnits = $this->subcourse->subcourseCourseUnits;
        if (empty($courseUnits))
            return null;
        $courseUnit = $courseUnits[0];
        //$schedule = MCourseSchedule::findOne('group_id = :group_id AND UNIX_TIMESTAMP(start_time) >= :start_time', [':group_id' => $this->group_id, ':start_time'=>time()]);//'course_unit_id' => $courseUnit->course_unit_id]);
        $schedule = MCourseSchedule::findBySql('SELECT * FROM yss_course_schedule WHERE group_id = :group_id AND UNIX_TIMESTAMP(start_time) >= :start_time LIMIT 1', [':group_id' => 1, ':start_time' => time()])->one();
        if (empty($schedule))
            return null;
        $rschedule['start_time'] = $schedule->start_time;
        $rschedule['course_unit_id'] = $schedule->course_unit_id;
        $rschedule['teacher_id'] = $schedule->teacher_id;
        return $rschedule;
    }

}
