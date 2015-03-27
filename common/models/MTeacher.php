<?php

namespace common\models;

use Yii;
use backend\behaviors\UploadBehavior;
use backend\behaviors\GetPhotoBehavior;
//use backend\behaviors\GetPhotoTrait;


/*
#教师
DROP TABLE IF EXISTS yss_teacher;
CREATE TABLE IF NOT EXISTS yss_teacher (
    teacher_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    school_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '学校id',
    name VARCHAR(16) NOT NULL DEFAULT '' COMMENT '姓名',    
    sex CHAR(1) NOT NULL DEFAULT '' COMMENT '姓别F/M',    
    birthday date NOT NULL COMMENT '生日',
    nationality VARCHAR(32) NOT NULL DEFAULT '' COMMENT '国藉',
    identify_id CHAR(32) NOT NULL DEFAULT '' COMMENT '身份Id',    
    onboard_time TIMESTAMP NOT NULL DEFAULT 0 COMMENT '入职时间',
    des TEXT NOT NULL DEFAULT '' COMMENT '教师简介',
    head_url VARCHAR(256) NOT NULL DEFAULT '',
    body_url VARCHAR(256) NOT NULL DEFAULT '',    
    sort_order int(10) unsigned NOT NULL DEFAULT 0 COMMENT '显示序号,大号显示在前',
    create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE yss_teacher ADD body_url VARCHAR(256) NOT NULL DEFAULT '' after des;
ALTER TABLE yss_teacher ADD head_url VARCHAR(256) NOT NULL DEFAULT '' after des;

ALTER TABLE yss_teacher ADD mobile VARCHAR(32) NOT NULL DEFAULT '' after des;

*/

class MTeacher extends \yii\db\ActiveRecord
{
	//	use GetPhotoTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sex', 'birthday'], 'required'],
            [['birthday', 'onboard_time', 'create_time'], 'safe'],
            [['des'], 'string'],
            [['name'], 'string', 'max' => 16],
            [['sex'], 'string', 'max' => 1],
            [['mobile'], 'match', 'pattern' => '/^((\+86)|(86))?1\d{10}$/'],            
            [['nationality', 'identify_id'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'school_id' => Yii::t('app', 'School ID'),			            
            'name' => Yii::t('app', 'Name'),
            'sex' => Yii::t('app', 'Sex'),
            'birthday' => Yii::t('app', 'Birthday'),
            'nationality' => Yii::t('app', 'Nationality'),
            'identify_id' => Yii::t('app', 'Identify ID'),
            'onboard_time' => Yii::t('app', 'Onboard Time'),
            'des' => Yii::t('app', 'Des'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'head_url' => Yii::t('app', 'Head URL'),
            'body_url' => Yii::t('app', 'Body URL'),            
            'create_time' => Yii::t('app', 'Create Time'),
        ];
    }

	public function getCourses()
	{
		$courseSchedules = $this->courseSchedules;
		$courses = [];
		foreach($courseSchedules as $courseSchedule) {
			$courses[] = $courseSchedule->group->course;
		}

        $rows = $courses;
        $courses = [];
        foreach($rows as $row) {
            if (empty($courses[$row->course_id])) {
                $courses[$row->course_id] = $row;
            }
        }
        return array_values($courses);
	}

	public function getStudents($merge=true)
	{
		$courseSchedules = $this->courseSchedules;
		$students = [];
		foreach($courseSchedules as $courseSchedule) {
			$groupStudents = $courseSchedule->group->groupStudents;
			foreach($groupStudents as $groupStudent) {
				$students[$courseSchedule->group->course->course_id][] = $groupStudent->student;
			}
		}
		if ($merge) {
            $s = [];
            foreach($students as $rows) {
                foreach($rows as $row) {
                    $s[] = $row;
                }
            }
            $rows = $s;
            $students = [];
            foreach($rows as $row) {
                if (empty($students[$row->student_id])) {
                    $students[$row->student_id] = $row;
                }
            }
            return array_values($students);
		}
		
		return $students;	
	}

	public function getCourseSchedules()
	{
		return $this->hasMany(MCourseSchedule::className(), ['teacher_id' => 'teacher_id']);
	}

	public function getHeadUrl()
	{
		return Yii::getAlias('@storageUrl').'/logo/'.$this->head_url;
	}

	public function getBodyUrl()
	{
		return Yii::getAlias('@storageUrl').'/logo/'.$this->body_url;
	}

	public function behaviors()
	{
		return [
			'uploadBehavior' => [
				'class' => UploadBehavior::className(),
				'attributes' => [
					'head_url' => [
						'tempPath' => '@backend/runtime/tmp',
						'path' => '@storage/logo',
						'url' => Yii::getAlias('@storageUrl').'/logo',
					],
					
					'body_url' => [
						'tempPath' => '@backend/runtime/tmp',
						'path' => '@storage/logo',
						'url' => Yii::getAlias('@storageUrl').'/logo',
					]
					
				]
			],
			
			'GetPhotoBehavior' => [
				'class' => GetPhotoBehavior::className(),
			]
			
		];
	}

	public static function getDropDownList($school_id)
	{
		//return \yii\helpers\ArrayHelper::map(MTeacher::find()->where(['school_id'=>$school_id])->all(),'teacher_id','name');
		$teachers = MTeacher::find()->where(['school_id'=>$school_id])->all();
		$list = [];
		foreach ($teachers as $teacher) {
			$list[$teacher->teacher_id] = "{$teacher->name} [{$teacher->teacher_id}]";
		}
		return $list;
	}

	public function getOwnerCat()
	{
		return MPhotoOwner::PHOTO_OWNER_TEACHER;
	}

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

