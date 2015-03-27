<?php

namespace common\models;

use Yii;

/*
#教室
DROP TABLE IF EXISTS yss_room;
CREATE TABLE IF NOT EXISTS yss_room (
    room_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    school_branch_id int(10) unsigned NOT NULL DEFAULT 0 COMMENT '分校id',
    title VARCHAR(256) NOT NULL DEFAULT '' COMMENT '教室名称',
    is_delete tinyint(3) unsigned NOT NULL DEFAULT 0,
    CONSTRAINT FOREIGN KEY (school_branch_id) REFERENCES yss_school_branch (school_branch_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;

*/

class MRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_branch_id', 'is_delete'], 'integer'],
            [['title'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'room_id' => Yii::t('app', 'Room ID'),
            'school_branch_id' => Yii::t('app', 'School Branch ID'),
            'title' => Yii::t('app', 'Title'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolBranch()
    {
        return $this->hasOne(MSchoolBranch::className(), ['school_branch_id' => 'school_branch_id']);
    }
}
