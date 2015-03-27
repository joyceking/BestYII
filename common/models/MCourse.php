<?php

/*
#课程
DROP TABLE IF EXISTS yss_course;
CREATE TABLE IF NOT EXISTS yss_course (
    course_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    school_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '学校id',
    title VARCHAR(256) NOT NULL DEFAULT '' COMMENT '课程名称',
    des TEXT NOT NULL DEFAULT '' COMMENT '课程介绍',
    object TEXT NOT NULL DEFAULT '' COMMENT '目标',
    feature TEXT NOT NULL DEFAULT '' COMMENT '特色',
    target_student TEXT NOT NULL DEFAULT '' COMMENT '适合人群',
    website TEXT NOT NULL DEFAULT '' COMMENT '网址',
    course_unit_is_over tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0:初始, 1:此课程的课时已制定完毕，可以制作计划了',
    is_delete tinyint(3) unsigned NOT NULL DEFAULT 0,
    type VARCHAR(128) NOT NULL DEFAULT '' COMMENT '类别名',
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


ALTER TABLE `yss_course` ADD `type` CHAR(128) NOT NULL COMMENT '类别名' AFTER `title`;

*/
namespace common\models;

use Yii;
use backend\behaviors\GetPhotoBehavior;
use common\models\MSchool;

class MCourse extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'yss_course';
    }

    public function rules()
    {
        return [
            [['des'], 'required'],
            [['des','object','feature','target_student','website','type'], 'string'],
            [['is_delete', 'course_unit_is_over'], 'integer'],
            [['title'], 'string', 'max' => 256]
        ];
    }

    public function attributeLabels()
    {
        return [
            'school_id' => Yii::t('app', 'School ID'),			
            'course_id' => Yii::t('app', 'Course ID'),
            'title' => Yii::t('app', 'Title'),
            'des' => Yii::t('app', 'Des'),
            'object' => Yii::t('app', 'Object'),
            'feature' => Yii::t('app', 'Feature'),
            'target_student' => Yii::t('app', 'Target Student'),
            'website' => Yii::t('app', 'Website'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'course_unit_is_over' => Yii::t('app', 'Course Unit Is Over'),
            'type' => Yii::t('app', 'type'),
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

    public function getCourseUnits()
    {
        return $this->hasMany(MCourseUnit::className(), ['course_id' => 'course_id']);
    }

    public function getSubcourses()
    {
        return $this->hasMany(MSubcourse::className(), ['course_id' => 'course_id']);
    }

	public function saveCourseUnit($model)
	{
		$model->course_id = $this->course_id;
		return $model->save();
	}

/*
//hehb
    public function getStudentCourses()
    {
        return $this->hasMany(MStudentSubcourse::className(), ['course_id' => 'course_id']);
    }

    public function getStudents()
    {
        $students = [];
        $studentCourses = $this->studentCourses;    
        foreach($studentCourses as $studentCourse)
            $students[] = $studentCourse->student;
        return $students;
    }
//
*/

    public function getCourseFullInfo()
    {
		return $this->title. ' ['. count($this->courseUnits)."课时" . ']';
    }

	public function isAvailable()
	{
		return $this->course_unit_is_over ? true : false;
	}	

	public function getOwnerCat()
	{
		return MPhotoOwner::PHOTO_OWNER_COURSE;
	}

    public static function getCourseTypeOptionName($key=null)
    {
        $arr = array(
            '托班' => '托班',
            '早教' => '早教',
            '艺术' => '艺术',
            '语言' => '语言',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getAllCourses()
    {
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $courses = $school->courses;
        return $courses;
    }

    public static function getAllSubcourses()
    {
        $courses = static::getAllCourses();
        $subCourses = [];
        foreach($courses as $course) {
            $subCourses = array_merge($subCourses, $course->subcourses);    
        }
        return $subCourses;
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
}
