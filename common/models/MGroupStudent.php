<?php

namespace common\models;

use Yii;

/*
#班与学生关系表, 一对多，即一个班有多少个学生
DROP TABLE IF EXISTS yss_group_student;
CREATE TABLE IF NOT EXISTS yss_group_student (
    group_student_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
	group_id int(10) unsigned NOT NULL DEFAULT 0,
	student_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '报了相同课程的学生才能组成一个班, 一个学生如报多门课程则属于多个班'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;
*/

class MGroupStudent extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'yss_group_student';
    }

    public function rules()
    {
        return [
            [['group_id', 'group_student_id', 'student_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'group_student_id' => Yii::t('app', 'GroupStudent ID'),
            'group_id' => Yii::t('app', 'Group ID'),
            'student_id' => Yii::t('app', 'Student ID'),
        ];
    }

	public function getStudent()
	{
		return $this->hasOne(MStudent::className(), ['student_id' => 'student_id']);
	}

	public function getGroup()
	{
		return $this->hasOne(MGroup::className(), ['group_id' => 'group_id']);
	}

}
