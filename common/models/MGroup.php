<?php

namespace common\models;

use Yii;
use backend\modules\wx\models\U;
use backend\behaviors\GetPhotoBehavior;

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

class MGroup extends \yii\db\ActiveRecord
{
	const GROUP_STATUS_NONE = 0;
	const GROUP_STATUS_SCHEDULE_DONE = 1;
	const GROUP_STATUS_END = 9;

	public $startTime;
	public $teacher_id;

    public function init()
    {
    	$this->startTime = date("Y-m-d H:i:s");
    }	
	
    public static function tableName()
    {
        return 'yss_group';
    }

    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 256],
            [['group_id', 'course_unit_id', 'status'], 'integer'],
            [['startTime'], 'string', 'max' => 32],            
            [['teacher_id'], 'integer'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'group_id' => Yii::t('app', 'Group ID'),
            'title' => Yii::t('app', 'Title'),
            'course_unit_id' => Yii::t('app', 'Course Unit ID'),
            'status' => Yii::t('app', 'Status'),
            'create_time' => Yii::t('app', 'Create Time'),
            //'startTime' => Yii::t('app', 'Group First Start Time'),            
            'startTime' => Yii::t('app', 'Group Course Unit Start Time'),
            'teacher_id' => Yii::t('app', 'Teacher Name'),                        
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

    public function getCourseUnit()
    {
        return $this->hasOne(MCourseUnit::className(), ['course_unit_id' => 'course_unit_id']);
    }

    public function getCourse()
    {
        return $this->courseUnit->course;
    }

    public function getGroupStudents()
    {
        return $this->hasMany(MGroupStudent::className(), ['group_id' => 'group_id']);
    }

	public function saveGroupStudent($model)
	{
		$model->group_id = $this->group_id;
		return $model->save();
	}

    // calculate how many students can apply this group
    public function getFreeStudents()
    {
        $groupStudents = $this->groupStudents;
        $groupStudentIds = \yii\helpers\ArrayHelper::getColumn($groupStudents, 'student_id');
        $freeStudents = [];
        $students = $this->courseUnit->getAppliedCourseUnitStudents();
        foreach($students as $student) {
            if (!in_array($student->student_id, $groupStudentIds)) {
                $freeStudents[] = $student;
            }                
        }
        return $freeStudents;
    }

	public function afterDelete()
	{
		$courseSchedules = $this->courseSchedules;
		foreach($courseSchedules as $courseSchedule) {
			$courseSchedule->delete();
		}
		
		$this->trigger(self::EVENT_AFTER_DELETE);
	}

	public function createCourseUnitSchedules($startTime, $teacher_id=0)
	{
		if (empty($startTime)) {
			$startTime = date("Y-m-d H:i:s");				
		}
	    $courseUnit = $this->courseUnit;
    	$schedule = MCourseSchedule::findOne(['group_id'=>$this->group_id, 'course_unit_id'=>$courseUnit->course_unit_id]);
		if ($schedule === null)
		{
			$schedule = new MCourseSchedule;			
			$schedule->group_id = $this->group_id;
			$schedule->course_unit_id = $courseUnit->course_unit_id;
			$schedule->teacher_id = $teacher_id;
			$schedule->start_time = $startTime;
			//$startTime = date("Y-m-d H:i:s", strtotime("+1 week", strtotime($startTime)));
			$schedule->save(false);				
		}
		$courseScheduleSignons = $schedule->createCourseScheduleSignons();			
	}
	
    public function getCourseSchedules()
    {
        return $this->hasMany(MCourseSchedule::className(), ['group_id' => 'group_id']);
    }

	public static function getGroupStatusOptionName($key=null)
	{
		$arr = array(
			self::GROUP_STATUS_NONE => '尚未开班',
			self::GROUP_STATUS_SCHEDULE_DONE => '已开班',
			self::GROUP_STATUS_END => '已结班',
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}

	public function startSchedule($startTime='', $teacher_id=0)
	{
        $this->createCourseUnitSchedules($startTime, $teacher_id);  
	}

	public function getOwnerCat()
	{
		return MPhotoOwner::PHOTO_OWNER_GROUP;
	}

}


/*
	//			else
	//				U::W(['group_id'=>$this->group_id, 'course_unit_id'=>$courseUnit->course_unit_id]);

	public function createCourseSchedules()
	{
		$courseUnits = $this->course->courseUnits;
		$schedules = [];
		foreach($courseUnits as $courseUnit)
		{
			$schedule = new MCourseSchedule;
			$schedule->group_id = $this->group_id;
			$schedule->course_unit_id = $courseUnit->course_unit_id;
			//$schedule->teacher_id = 0;
			//$schedule->room_id = 0;
			$schedule->start_time = date("Y-m-d H:i:s");
			$schedule->save(false);
			$schedules[] = $schedule;

			//create signons
			$courseScheduleSignons = $schedule->createCourseScheduleSignons();			
		}
		return $schedules;
	}
//		$this->status = MGroup::GROUP_STATUS_SCHEDULE_DONE;
//		$this->save(false);			
	
		public function getFirstStartTimeFromSchedule()
		{
			$courseUnits = $this->course->courseUnits;
			if (empty($courseUnits))
				return null;
			$courseUnit = $courseUnits[0];
			$schedule = MCourseSchedule::findOne(['group_id'=>$this->group_id, 'course_unit_id'=>$courseUnit->course_unit_id]);
			if (empty($schedule))
				return null;
			return $schedule->start_time;
		}

        public function createCourseSchedules($startTime, $teacher_id=0)
        {
            if (empty($startTime)) {
                $startTime = date("Y-m-d H:i:s");               
            }
    
            $courseUnits = $this->course->courseUnits;
            foreach($courseUnits as $courseUnit)
            {
                $schedule = MCourseSchedule::findOne(['group_id'=>$this->group_id, 'course_unit_id'=>$courseUnit->course_unit_id]);
                if ($schedule === null)
                {
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
                $courseScheduleSignons = $schedule->createCourseScheduleSignons();          
            }
        }
    */
        

