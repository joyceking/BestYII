<?php

namespace common\models;

use Yii;
use backend\modules\wx\models\U;

/*
  #课时计划
  DROP TABLE IF EXISTS yss_course_schedule;
  CREATE TABLE IF NOT EXISTS yss_course_schedule (
  course_schedule_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  group_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '班id, 对班上的每个课时作教学计划',
  course_unit_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '课时ID，为了兼容暂不删除此字段，总是等于group中的course_unit_id',
  teacher_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '教师id',
  room_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '教室id',
  start_time TIMESTAMP NOT NULL DEFAULT 0 COMMENT '开始时间',
  is_repay tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '是补课',
  UNIQUE KEY idx_group_id_course_unit_id(group_id, course_unit_id)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

 */

class MCourseSchedule extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'yss_course_schedule';
    }

    public function rules() {
        return [
            [['course_schedule_id', 'teacher_id', 'group_id', 'course_unit_id', 'room_id', 'is_repay'], 'integer'],
            [['start_time'], 'safe']
        ];
    }

    public function attributeLabels() {
        return [
            'course_schedule_id' => Yii::t('app', 'Course Schedule ID'),
            'group_id' => Yii::t('app', 'Group ID'),
            'course_unit_id' => Yii::t('app', 'Course Unit ID'),
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'room_id' => Yii::t('app', 'Room ID'),
            'start_time' => Yii::t('app', 'Start Time'),
            'is_repay' => Yii::t('app', 'Is Repay'),
        ];
    }

    public function afterDelete() {
        $courseScheduleSignons = $this->courseScheduleSignons;
        foreach ($courseScheduleSignons as $courseScheduleSignon) {
            $courseScheduleSignon->delete();
        }

        $this->trigger(self::EVENT_AFTER_DELETE);
    }

    public function getCourseUnit() {
        return $this->hasOne(MCourseUnit::className(), ['course_unit_id' => 'course_unit_id']);
    }

    public function getCourseTitle() {
        return $this->hasOne(MCourse::className(), ['course_id' => 'course_id'])->viaTable(MCourseUnit::tableName(), ['course_unit_id' => 'course_unit_id']);
    }

    public function getGroup() {
        return $this->hasOne(MGroup::className(), ['group_id' => 'group_id']);
    }

    public function getClass() {
        return $this->hasOne(MClass::className(), ['group_id' => 'group_id']);
    }

    public function getTeacher() {
        return $this->hasOne(MTeacher::className(), ['teacher_id' => 'teacher_id']);
    }

    public function getRoom() {
        return $this->hasOne(MRoom::className(), ['room_id' => 'room_id']);
    }

    public function getCourseScheduleSignons() {
        return $this->hasMany(MCourseScheduleSignon::className(), ['course_schedule_id' => 'course_schedule_id']);
    }

    public function createCourseScheduleSignons() {
        $groupStudents = $this->group->groupStudents;
        foreach ($groupStudents as $groupStudent) {
            $signon = MCourseScheduleSignon::findOne(['course_schedule_id' => $this->course_schedule_id, 'student_id' => $groupStudent->student_id]);
            if ($signon === null) {
                $signon = new MCourseScheduleSignon;
                $signon->course_schedule_id = $this->course_schedule_id;
                $signon->student_id = $groupStudent->student_id;
                $signon->save(false);
            }
        }
    }

    /*
     * 争对会员单独创建签到表
     */

    public function createStudentScheduleSignons() {
        $groupStudents = $this->class->groupStudents;
        $courseSchedules = $this->find()->where(['group_id' => $this->group_id])->select('course_schedule_id,course_unit_id')->asArray()->all();
        foreach ($courseSchedules as $courseSchedule) {
            foreach ($groupStudents as $groupStudent) {
                $studentSubcourseUnits = $this->array_get_by_key($groupStudent->student->studentSubcourseUnits, 'course_unit_id');
                if (in_array('"' . $courseSchedule['course_unit_id'] . '"', $studentSubcourseUnits)) {
                    $signon = MCourseScheduleSignon::findOne(['course_schedule_id' => $courseSchedule['course_schedule_id'], 'student_id' => $groupStudent->student_id]);
                    if ($signon === null) {
                        $signon = new MCourseScheduleSignon;
                        $signon->course_schedule_id = $courseSchedule['course_schedule_id'];
                        $signon->student_id = $groupStudent->student_id;
                        $signon->save(false);
                    }
                }
            }
        }
    }

    protected function array_get_by_key(array $array, $string) {
        if (!trim($string))
            return false;
        preg_match_all("/\"$string\";\w{1}:(?:\d+:|)(.*?);/", serialize($array), $res);
        return $res[1];
    }

    public
            function getCourseUnitInfo() {
        return $this->courseUnit->title;
    }

}

/*
	public function createCourseScheduleSignons()
	{
		$groupStudents = $this->group->groupStudents;
		foreach($groupStudents as $groupStudent)
		{
			$signon = new MCourseScheduleSignon;
			$signon->course_schedule_id = $this->course_schedule_id;
			$signon->student_id = $groupStudent->student_id;
			$signon->save(false);
		}
	}
*/


