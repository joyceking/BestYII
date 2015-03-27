<?php

namespace common\models;

use Yii;
use backend\modules\wx\models\RespNewsItem;
use backend\behaviors\UploadBehavior;
use backend\modules\wx\models\U;

/*
DROP TABLE IF EXISTS yss_wx_action;
CREATE TABLE IF NOT EXISTS yss_wx_action (
    gh_id VARCHAR(32) NOT NULL DEFAULT '',
    wx_action_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    keyword VARCHAR(128) NOT NULL DEFAULT '',
    type VARCHAR(32) NOT NULL DEFAULT '',
    action TEXT,
    KEY idx_gh_id(gh_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
*/

class MWxAction extends \yii\db\ActiveRecord
{
	const WX_ACTION_TYPE_TEXT = 'text';
	const WX_ACTION_TYPE_NEWS = 'news';
	const WX_ACTION_TYPE_FORWARD = 'forward';

	public $content;
	
	public $title;	
	public $description;
	public $picUrl;
	public $url;
	private $_old_picUrl;

	public $forward_url;
	public $token;

	const WX_ACTION_RESP_IMAGE_PATH = 'logo';

    public static function tableName()
    {
        return 'yss_wx_action';
    }

    public function rules()
    {
        return [
			[['action'], 'string'],
			[['gh_id', 'type'], 'string', 'max' => 32],
			[['keyword'], 'string', 'max' => 128],
			[['content', 'title', 'description', 'picUrl', 'url'], 'string'],
			[['forward_url', 'token'], 'string'],
		];
    }

    public function attributeLabels()
    {
        return [
            'gh_id' => Yii::t('backend', 'Gh ID'),
			'wx_action_id' => Yii::t('backend', 'Wx Action ID'),
			'keyword' => Yii::t('backend', 'Keyword'),
			'type' => Yii::t('backend', 'Type'),
			'action' => Yii::t('backend', 'Action'),
		];
    }

	public function behaviors()
	{
		return [
			'uploadBehavior' => [
				'class' => UploadBehavior::className(),
				'attributes' => [
					'picUrl' => [
						'tempPath' => '@backend/runtime/tmp',
						'path' => '@storage' . '/' . self::WX_ACTION_RESP_IMAGE_PATH,
						'url' => Yii::getAlias('@storageUrl') . '/' . self::WX_ACTION_RESP_IMAGE_PATH,
						//'path' => '@storage/logo',
						//'url' => Yii::getAlias('@storageUrl').'/logo',

					],
				]
			]
		];
	}

    public function isAttributeChanged($name)
    {
    	if ($name == 'picUrl') {
			return $this->_old_picUrl != $this->picUrl;
		}
		return parent::isAttributeChanged($name);
    }

	public function hasAttribute($name)
	{
		if (!empty($this->$name))
			return true;
		return parent::hasAttribute($name);
	}

	public function getAttribute($name)
	{
		return isset($this->$name) ? $this->$name : parent::getAttribute($name);
	}

	public function setAttribute($name, $value)
	{
		if (isset($this->$name))
			$this->$name = $value;
		else
			parent::setAttribute($name, $value);
	}

	public static function getActionTypeOptionName($key=null)
	{
		$arr = array(
			self::WX_ACTION_TYPE_TEXT => '文本',
			self::WX_ACTION_TYPE_NEWS => '图文',
			self::WX_ACTION_TYPE_FORWARD => '转发',
		);		  
		return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
	}

    public function afterFind()
    {
    	if (empty($this->action)) {
			return;
    	}
    	$arr = json_decode($this->action, true);
		if ($this->type == self::WX_ACTION_TYPE_TEXT) {
			$this->content = $this->action;
		} elseif ($this->type == self::WX_ACTION_TYPE_NEWS) {
			$arr = json_decode($this->action, true);
			$this->title = $arr['title'];
			$this->description = $arr['description'];
			$this->picUrl = $arr['picUrl'];
			$this->url = $arr['url'];

			$this->_old_picUrl = $this->picUrl;
		} elseif ($this->type == self::WX_ACTION_TYPE_FORWARD) {
			$arr = json_decode($this->action, true);
			$this->forward_url = $arr['forward_url'];
			$this->token = $arr['token'];
		}
        return parent::afterFind();
    }	

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			$this->keyword = trim($this->keyword);
			if ($this->type == self::WX_ACTION_TYPE_TEXT) {
				$this->action = $this->content;
			} elseif ($this->type == self::WX_ACTION_TYPE_NEWS) {
				$this->action = json_encode(['title'=>$this->title, 'description'=>$this->description, 'picUrl'=>$this->picUrl, 'url'=>$this->url], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
			} elseif ($this->type == self::WX_ACTION_TYPE_FORWARD) {
				$this->action = json_encode(['forward_url'=>$this->forward_url, 'token'=>$this->token], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
			}
			return true;
		}
		return false;
	}

	public function getResp($wechat)
	{
		$FromUserName = $wechat->getRequest('FromUserName');
		$openid = $wechat->getRequest('FromUserName');
		$gh_id = $wechat->getRequest('ToUserName');
		$MsgType = $wechat->getRequest('MsgType');
		$Event = $wechat->getRequest('Event');
		$EventKey = $wechat->getRequest('EventKey');
		$user = $wechat->getUser();
		$dict = [
				'{nickname}' => empty($user->nickname) ? '' : $user->nickname,
				'{openid}' => $openid,
				'{gh_id}' => $gh_id,
			];
		if ($this->type == self::WX_ACTION_TYPE_TEXT) {
			$content = strtr($this->content, $dict);
			return $wechat->responseText($content);
		} elseif ($this->type == self::WX_ACTION_TYPE_NEWS) {
			$title = strtr($this->title, $dict);
			$description = strtr($this->description, $dict);
			$picUrl = Yii::getAlias('@storageUrl') . '/' . self::WX_ACTION_RESP_IMAGE_PATH . '/' . strtr($this->picUrl, $dict);
			$url = strtr($this->url, $dict);
			$items = [
				new RespNewsItem($title, $description, $picUrl, $url),
			];
			return $wechat->responseNews($items);
		} elseif ($this->type == self::WX_ACTION_TYPE_FORWARD) {
			$forward_url = strtr($this->forward_url, $dict);
			$token = strtr($this->token, $dict);
			return $wechat->procRemote(['apiurl'=>$forward_url, 'token'=>$token, 'key'=>$this->keyword]);
		}

		return false;
	}

}
