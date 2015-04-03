<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use common\models\MSchool;
use common\models\MPhotoOwner;
use backend\modules\wx\models\RespNews;
use backend\modules\wx\models\RespNewsItem;
use backend\modules\wx\models\U;
use backend\behaviors\GetPhotoBehavior;

/*
  #学生
  DROP TABLE IF EXISTS yss_student;
  CREATE TABLE IF NOT EXISTS yss_student (
  student_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  school_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '学校id',
  school_branch_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '分校id',
  name VARCHAR(16) NOT NULL DEFAULT '' COMMENT '姓名',
  sex CHAR(1) NOT NULL DEFAULT '' COMMENT '姓别F/M',
  birthday date NOT NULL COMMENT '生日',
  nationality VARCHAR(32) NOT NULL DEFAULT '' COMMENT '国藉',
  create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  mobile VARCHAR(32) NOT NULL DEFAULT '',
  is_delete tinyint(3) unsigned NOT NULL DEFAULT 0
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

 */

class MStudent extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'yss_student';
    }

    public function rules() {
        return [
            [['school_id', 'school_branch_id', 'is_delete'], 'integer'],
            [['birthday', 'name'], 'required'],
            [['birthday', 'create_time'], 'safe'],
            [['name'], 'string', 'max' => 16],
            [['sex'], 'string', 'max' => 1],
            [['mobile'], 'match', 'pattern' => '/^((\+86)|(86))?1\d{10}$/'],
            [['nationality', 'mobile'], 'string', 'max' => 32]
        ];
    }

    public function attributeLabels() {
        return [
            'student_id' => Yii::t('app', 'Student ID'),
            'school_id' => Yii::t('app', 'School ID'),
            'name' => Yii::t('app', 'Name'),
            'sex' => Yii::t('app', 'Sex'),
            'birthday' => Yii::t('app', 'Birthday'),
            'nationality' => Yii::t('app', 'Nationality'),
            'create_time' => Yii::t('app', 'Create Time'),
            'mobile' => Yii::t('app', 'Mobile'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    public function behaviors() {
        return [
            'GetPhotoBehavior' => [
                'class' => GetPhotoBehavior::className(),
            ]
        ];
    }

    public function getCourseScheduleSignons() {
        return $this->hasMany(MCourseScheduleSignon::className(), ['student_id' => 'student_id']);
    }

    public function getGroups() {
        $groupStudents = $this->groupStudents;
        $groups = [];
        foreach ($groupStudents as $groupStudent) {
            if (!empty($groupStudent->group))
                $groups[] = $groupStudent->group;
        }
        return $groups;
    }

    public function getGroupStudents() {
        return $this->hasMany(MGroupStudent::className(), ['student_id' => 'student_id']);
    }

    public function getParents() {
        return $this->hasMany(MParent::className(), ['student_id' => 'student_id']);
    }

    public function getSchool() {
        return $this->hasOne(MSchool::className(), ['school_id' => 'school_id']);
    }

    public function getSchoolBranch() {
        return $this->hasOne(MSchoolBranch::className(), ['school_branch_id' => 'school_branch_id']);
    }

    /*
      public function getStudentCourses()
      {
      return $this->hasMany(MStudentCourse::className(), ['student_id' => 'student_id']);
      }

      public function saveStudentCourse($model)
      {
      $model->student_id = $this->student_id;
      return $model->save();
      }
     */
    /*
     * 获取子课程
     */

    public function getSubCourseUnit() {
        return $this->hasMany(MStudentSubcourse::className(), ['student_id' => 'student_id']);
    }

    public function getStudentSubcourseUnits() {
        return $this->hasMany(MSubcourseCourseUnit::className(), ['subcourse_id' => 'subcourse_id'])->viaTable(MStudentSubcourse::tableName(), ['student_id' => 'student_id'])->asArray();
    }

    public function getStudentSubcourses() {
        return $this->hasMany(MStudentSubcourse::className(), ['student_id' => 'student_id']);
    }

    public function getSubcourses() {
        $subcourses = [];
        foreach ($this->studentSubcourses as $studentSubcourse) {
            $subcourses[] = $studentSubcourse->subcourse;
        }
        return $subcourses;
    }

    public function saveStudentSubcourse($model) {
        $model->student_id = $this->student_id;
        return $model->save();
    }

    public function getCourseSchedulesX() {
        $schedules = [];
        $groups = $this->getGroups();
        foreach ($groups as $group) {
            if ($group->status == MGroup::GROUP_STATUS_NONE)
                continue;
            $courseSchedules = $group->courseSchedules;
            $schedules[$group->group_id] = $courseSchedules;
        }
        return $schedules;
    }

    public function getCourseSchedulesY() {
        $bbb = [];
        $subcourses = $this->subcourses;
        foreach ($subcourses as $subcourse) {
            $subcourseCourseUnits = $subcourse->subcourseCourseUnits;
            foreach ($subcourseCourseUnits as $subcourseCourseUnit) {
                $bbb[$subcourse->subcourse_id][] = $subcourseCourseUnit->courseUnit->course_unit_id;
            }
        }

        $courseSchedulesX = [];
        $courseSchedules = $this->getCourseSchedulesX();
        foreach ($courseSchedules as $group_id => $courseSchedule) {
            $group = MGroup::findOne($group_id);
            if (empty($group)) {
                continue;
            }
            foreach ($bbb as $subcourse_id => $course_unit_ids) {
                foreach ($course_unit_ids as $course_unit_id) {
                    if ($group->course_unit_id == $course_unit_id) {
                        $courseSchedulesX[$subcourse_id][] = $courseSchedule;
                        break;
                    }
                }
            }
        }
//        U::W($courseSchedulesX);  
        return $courseSchedulesX;
    }

    public function getCourseScheduleSignonsX() {
        $signons = [];
        $groups = $this->getGroups();
        foreach ($groups as $group) {
            if ($group->status == MGroup::GROUP_STATUS_NONE)
                continue;

            $courseSchedules = $group->courseSchedules;
            $aaa = [];
            foreach ($courseSchedules as $courseSchedule) {
                $courseScheduleSignons = $courseSchedule->courseScheduleSignons;
                foreach ($courseScheduleSignons as $courseScheduleSignon) {
                    if ($courseScheduleSignon->student_id == $this->student_id) {
                        $aaa[] = $courseScheduleSignon;
                        break;
                    }
                }
            }
            $signons[$group->group_id] = $aaa;
        }
        //U::W($signons);		
        return $signons;
    }

    public function getCourseScheduleSignonsY() {
        $bbb = [];
        $subcourses = $this->subcourses;
        foreach ($subcourses as $subcourse) {
            $subcourseCourseUnits = $subcourse->subcourseCourseUnits;
            foreach ($subcourseCourseUnits as $subcourseCourseUnit) {
                $bbb[$subcourse->subcourse_id][] = $subcourseCourseUnit->courseUnit->course_unit_id;
            }
        }

        $signonsX = [];
        $signons = $this->getCourseScheduleSignonsX();
        foreach ($signons as $group_id => $signon) {
            $group = MGroup::findOne($group_id);
            if (empty($group)) {
                continue;
            }
            foreach ($bbb as $subcourse_id => $course_unit_ids) {
                foreach ($course_unit_ids as $course_unit_id) {
                    if ($group->course_unit_id == $course_unit_id) {
                        $signonsX[$subcourse_id][] = $signon;
                        break;
                    }
                }
            }
        }
//        U::W($signonsX);  
        return $signonsX;
    }

    public function getOwnerCat() {
        return MPhotoOwner::PHOTO_OWNER_STUDENT;
    }

    public function getOwnerId() {
        return $this->hasMany(MOpenidOwner::className(), ['owner_id' => 'student_id'])->where(['owner_cat' => 1])->asArray();
    }

    /*
      public function getPhotosCount($tags = false, $limit = false)
      {
      $params = [':owner_cat' => $this->ownerCat, ':owner_id' => $this->primaryKey];
      $sql = <<<EOD
      SELECT COUNT(*) from yss_photo_owner t1
      INNER JOIN yss_photo t2 on t1.photo_id = t2.photo_id
      WHERE t1.owner_cat = :owner_cat AND t1.owner_id = :owner_id
      EOD;
      $count = Yii::$app->db->createCommand($sql, $params)->queryScalar();
      return $count;
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
    /*
     * 获取课程计划表
     */
    public function getCourseSchedules(){
        return $this->hasMany(MCourseSchedule::className(), ['course_schedule_id'=>'course_schedule_id'])->viaTable(MCourseScheduleSignon::tableName(),['student_id'=>'student_id']);
    }
}
