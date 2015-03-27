<?php

namespace common\models;

use Yii;

/*
CREATE TABLE IF NOT EXISTS yss_subcourse (
    subcourse_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    course_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '此子课程属于哪个课程',
    title VARCHAR(256) NOT NULL DEFAULT '' COMMENT '子课程名',
    CONSTRAINT FOREIGN KEY (course_id) REFERENCES yss_course (course_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

*/

/**
 * This is the model class for table "yss_subcourse".
 *
 * @property string $subcourse_id
 * @property string $course_id
 * @property string $title
 */
class MSubcourse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_subcourse';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['course_id'], 'integer'],
            
            [['title'], 'string', 'max' => 256]
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subcourse_id' => Yii::t('backend', 'Subcourse ID'),
            
            'course_id' => Yii::t('backend', 'Course_ID'),
            
            'title' => Yii::t('backend', 'Title'),
        
        ];
    }

    public function getSubcourseCourseUnits()
    {
        return $this->hasMany(MSubcourseCourseUnit::className(), ['subcourse_id' => 'subcourse_id']);
    }

    public function getCourse()
    {
        return $this->hasOne(MCourse::className(), ['course_id' => 'course_id']);
    }

    public function getStudentSubcourses()
    {
        return $this->hasMany(MStudentSubcourse::className(), ['subcourse_id' => 'subcourse_id']);
    }

	public function saveSubcourseCourseUnit($model)
	{
		$model->subcourse_id = $this->subcourse_id;		
		return $model->save();
	}
    
}
