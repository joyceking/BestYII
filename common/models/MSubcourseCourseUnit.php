<?php

namespace common\models;

use Yii;

/*
CREATE TABLE IF NOT EXISTS yss_subcourse_course_unit (
    subcourse_course_unit_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    subcourse_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '子课程ID',
    course_unit_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '子课程所对应的课时ID，一个子课程包括多个课时',
    CONSTRAINT FOREIGN KEY (subcourse_id) REFERENCES yss_subcourse (subcourse_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (course_unit_id) REFERENCES yss_course_unit (course_unit_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
*/

/**
 * This is the model class for table "yss_subcourse_course_unit".
 *
 * @property string $subcourse_course_unit_id
 * @property string $subcourse_id
 * @property string $course_unit_id
 */
class MSubcourseCourseUnit extends \yii\db\ActiveRecord
 {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_subcourse_course_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['subcourse_id', 'course_unit_id'], 'integer']
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subcourse_course_unit_id' => Yii::t('backend', 'Subcourse Course Unit ID'),
            
            'subcourse_id' => Yii::t('backend', 'Subcourse ID'),
            
            'course_unit_id' => Yii::t('backend', 'Course Unit ID'),
        
        ];
    }

    public function getSubcourse()
    {
        return $this->hasOne(MSubcourse::className(), ['subcourse_id' => 'subcourse_id']);
    }

    public function getCourseUnit()
    {
        return $this->hasOne(MCourseUnit::className(), ['course_unit_id' => 'course_unit_id']);
    }
    
}
