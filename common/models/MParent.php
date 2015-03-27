<?php

namespace common\models;

use Yii;

/*
#家长
CREATE TABLE IF NOT EXISTS yss_parent (
    parent_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '学生id',
    name VARCHAR(16) NOT NULL DEFAULT '' COMMENT '姓名',    
    sex CHAR(1) NOT NULL DEFAULT '' COMMENT '姓别F/M',    
    mobile CHAR(32) NOT NULL DEFAULT '',
    addr CHAR(128) NOT NULL DEFAULT '',
    qq CHAR(32) NOT NULL DEFAULT '',
    email CHAR(64) NOT NULL DEFAULT '',
    is_delete tinyint(3) unsigned NOT NULL DEFAULT 0,
    CONSTRAINT FOREIGN KEY (student_id) REFERENCES yss_student (student_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

*/

class MParent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_parent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'is_delete'], 'integer'],
            [['name'], 'string', 'max' => 16],
            [['sex'], 'string', 'max' => 1],
            [['mobile', 'qq'], 'string', 'max' => 32],
            [['addr'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent_id' => Yii::t('app', 'Parent ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'name' => Yii::t('app', 'Name'),
            'sex' => Yii::t('app', 'Sex'),
            'mobile' => Yii::t('app', 'Mobile'),
            'addr' => Yii::t('app', 'Addr'),
            'qq' => Yii::t('app', 'Qq'),
            'email' => Yii::t('app', 'Email'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(MStudent::className(), ['student_id' => 'student_id']);
    }
}
