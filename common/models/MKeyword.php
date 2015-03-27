<?php

namespace common\models;

/*
DROP TABLE IF EXISTS yss_keyword;
CREATE TABLE IF NOT EXISTS yss_keyword (
    keyword_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    keyword VARCHAR(256) NOT NULL DEFAULT '',
	value TEXT,
    comment TEXT,
    create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_time TIMESTAMP NOT NULL,
    UNIQUE KEY idx_keyword(keyword)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO yss_keyword (keyword,value,comment) VALUES ('api_1_key', '最美园丁', '接口#1绑定的关键词');
INSERT INTO yss_keyword (keyword,value,comment) VALUES ('api_1_apiurl', 'http://58.221.61.118/api.php?hash=10af0', '接口#1的Url');
INSERT INTO yss_keyword (keyword,value,comment) VALUES ('api_1_token', '7e846dca0192226fd6f4ff9c1c17cd11', '接口#1的Token');

INSERT INTO yss_keyword (keyword,value,comment) VALUES ('api_2_key', '活动', '接口#2绑定的关键词');
INSERT INTO yss_keyword (keyword,value,comment) VALUES ('api_2_apiurl', 'http://w.55wh.com/index.php?g=Home&m=Weixin&a=index&token=ukutpm1417493212', '接口#2的Url');
INSERT INTO yss_keyword (keyword,value,comment) VALUES ('api_2_token', 'vPtryntxrQqQnp8e8Ewm', '接口#2的Token');

INSERT INTO yss_keyword (keyword,value,comment) VALUES ('reserve_hint', '', '预定提示信息');

INSERT INTO yss_keyword (keyword,value,comment) VALUES ('weixin_id', '', '微信服务号');
INSERT INTO yss_keyword (keyword,value,comment) VALUES ('contact_us', '', '联系我们');
INSERT INTO yss_keyword (keyword,value,comment) VALUES ('recommend_info', '', '推荐有礼提示信息');
*/

use Yii;

/**
 * This is the model class for table "yss_keyword".
 *
 * @property string $keyword_id
 * @property string $keyword
 * @property string $value
 * @property string $create_time
 * @property string $update_time
 */
class MKeyword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yss_keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
//			[['value'], 'required'],
            
			[['value'], 'string'],
            
			[['create_time', 'update_time'], 'safe'],
            
			[['keyword'], 'string', 'max' => 256],
            
			[['keyword'], 'unique']
        
		];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'keyword_id' => Yii::t('backend', 'Keyword ID'),
            
			'keyword' => Yii::t('backend', 'Keyword'),
            
			'value' => Yii::t('backend', 'Value'),
            
			'create_time' => Yii::t('backend', 'Create Time'),
            
			'update_time' => Yii::t('backend', 'Update Time'),
        
		];
    }
}
