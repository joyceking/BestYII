<?php

namespace backend\modules\wx\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;

use backend\modules\wx\models\U;
use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\WxException;
use backend\modules\wx\models\MAccessLog;
use backend\modules\wx\models\MAccessLogAll;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\MGh;
use backend\modules\wx\models\MOffice;
use backend\modules\wx\models\MStaff;
use backend\modules\wx\models\MGroup;
use backend\modules\wx\models\MSceneDetail;

use backend\modules\wx\models\RespText;
use backend\modules\wx\models\RespImage;
use backend\modules\wx\models\RespNews;
use backend\modules\wx\models\RespNewsItem;
use backend\modules\wx\models\RespMusic;
use backend\modules\wx\models\RespTransfer;

use common\models\MPhoto;
use common\models\MPhotoOwner;
use common\models\MOpenidOwner;
use common\models\MStudent;
use common\models\MTeacher;
use common\models\MCourseSchedule;
use common\models\MCourseScheduleSignon;

class WechatHoya extends Wechat
{

    protected function saveAccessLogAll($params=[]) 
    {
        $request = $this->getRequest();
        $log = new MAccessLogAll;
        $log->setAttributes($request, false);
        $log->save(false);
    }

    protected function saveAccessLog($params=[]) 
    {
        $request = $this->getRequest();
        //U::W($request);            
        $log = new MAccessLog;
        $log->setAttributes($request, false);
        $log->setAttributes($params, false);
        //U::W($log->getAttributes()); 
        $log->save(false);
    }
    
    protected function onSubscribe($isNewFan) 
    {            
        $FromUserName = $this->getRequest('FromUserName');    
        $openid = $this->getRequest('FromUserName');        
        $gh_id = $this->getRequest('ToUserName');                
        $MsgType = $this->getRequest('MsgType');
        $Event = $this->getRequest('Event');    
        $EventKey = $this->getRequest('EventKey');
        $user = $this->getUser();
		
        if (!empty($EventKey))
        {        
            //a new fan subscribe with qr parameter, EventKey:qrscene_3
            $Ticket = $this->getRequest('Ticket');    
            $scene_pid = substr($EventKey, 8);    
            $nickname = empty($user->nickname) ? '' : $user->nickname;            
            return $this->responseText("{$nickname}, 您好, 欢迎进入官方微信服务号.! ");
        }
        else
        {
            $nickname = empty($user->nickname) ? '' : $user->nickname;            
            return $this->responseText("{$nickname}, 您好, 欢迎进入官方微信服务号..! ");
        }
    }

    protected function onUnsubscribe() 
    { 
        $FromUserName = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');
        $user = $this->getUser();
        //$scene_pid = $user->scene_pid; 
        $user->subscribe = 0;
        //$user->gid = 0;
        //$user->msg_cnt = 0;
        $user->save(false);
        return Wechat::NO_RESP;            
    }

    const STATE_NONE = 'NONE';    
    const STATE_PLEASE_UPLOAD_IMAGE = 'STATE_PLEASE_UPLOAD_IMAGE';    
    const STATE_PLEASE_ENTER_SIGNON_ID = 'STATE_PLEASE_ENTER_SIGNON_ID';    	
    const STATE_PLEASE_ENTER_COMMENT = 'STATE_PLEASE_ENTER_COMMENT';    		

    protected function getState($key) 
    { 
		$openid = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');	    
        $key = "{$key}_{$gh_id}_{$openid}";
        $state = Yii::$app->cache->get($key);
        return $state === false ? self::STATE_NONE : $state;
    }
    
    protected function setState($key, $val)
    { 
		$openid = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');	    
        $key = "{$key}_{$gh_id}_{$openid}";
        Yii::$app->cache->set($key, $val, 3600);    
    }

    protected function deleteState($key)
    { 
		$openid = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');	    
        $key = "{$key}_{$gh_id}_{$openid}";
        Yii::$app->cache->delete($key);    
    }

	protected function getEnterSignonIdPrompt() 
	{ 
		$openid = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');	
		$teacher_id = $this->getState('TEACHER_ID');
		$this->setState('TEACHER_ID', $teacher_id);		
		$courseSchedules = MCourseSchedule::find()->where('teacher_id=:teacher_id AND DATE(start_time)=:start_time', [':teacher_id'=>$teacher_id, ':start_time'=>date("Y-m-d")])->orderBy('course_schedule_id')->all();
		if (empty($courseSchedules))
			return "今天无课,不用签到 p:-)\n, 0:退出";
		$signon_ids = [];
		$str = "Please select a signon id:\n";
		foreach($courseSchedules as $courseSchedule)
		{
			$str .= "{$courseSchedule->courseUnit->course->title} {$courseSchedule->courseUnit->title} \n";
			$signons = $courseSchedule->courseScheduleSignons;
			foreach($signons as $signon)
			{
				$str .= "    {$signon->signon_id}. {$signon->student->name} \n";
				$signon_ids[] = $signon->signon_id;
			}
		}
		$str .= "0. exit";
		$this->setState('SIGNON_RANGE_IDS', empty($signon_ids)?'':implode(':', $signon_ids));		
		return $str;		
	}

	protected function onText() 
	{ 
		$openid = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');	
		while(1)
		{
			$Content = $this->getRequest('Content');
			$msg = trim($Content);	
			$state = $this->getState('STATUS');
			if ($msg == '0' && $state != self::STATE_NONE)
			{
				$this->deleteState('STATUS');
				$this->deleteState('TEACHER_ID');
				$this->deleteState('MEDIA_ID');								
				$this->deleteState('SIGNON_RANGE_IDS');												
				$this->deleteState('SIGNON_ID');																
				return $this->responseText("Thank you, bye");
			}

			U::W($state);
			switch ($state) 
			{
				case self::STATE_NONE:
					if ($msg == 'Xy')
					{
						$model = $this->getUser();		
						if (empty($model->openidOwners)) 
							return $this->getBindHint();
						
						foreach ($model->openidOwners as $openidOwner) 
						{
							if ($openidOwner->owner_cat == MOpenidOwner::OPENID_OWNER_TEACHER) 
							{
								$teacher = MTeacher::findOne($openidOwner->owner_id);
								if (empty($teacher))
								{
									U::W(['Invalid teacher_id.', __METHOD__, $gh_id, $openid, $openidOwner->getAttributes()]);
									continue;
								}
								$this->setState('TEACHER_ID', $teacher->teacher_id);
								$this->setState('STATUS', self::STATE_PLEASE_UPLOAD_IMAGE);						
								return $this->responseText("请上传签到照片, 0:退出");
							}
						}		
						return $this->getBindHint();
					}					
					break;
					
				case self::STATE_PLEASE_ENTER_SIGNON_ID:
					if (!is_numeric($msg))
						return $this->responseText("无效的序号ID.\n\n".$this->getEnterSignonIdPrompt()); 

					$signon_ids = $this->getState('SIGNON_RANGE_IDS');
					if (empty($signon_ids))
						return $this->responseText("今天无课,不用签到, 0:退出"); 
					
					$signon_ids = explode(':', $signon_ids);
					if (!in_array($msg, $signon_ids))
						return $this->responseText("无效的序号ID\n\n".$this->getEnterSignonIdPrompt()); 
					
					$model = MCourseScheduleSignon::findOne($msg);
					if ($model === null)
						return $this->responseText("此签到ID不存在\n\n".$this->getEnterSignonIdPrompt()); 
					$this->setState('SIGNON_ID', $msg);
					$this->setState('STATUS', self::STATE_PLEASE_ENTER_COMMENT); 					
					//return $this->responseText("Upload successfully! Please upload next image, 0:exit"));														
					return $this->responseText("请输入评价, 0:退出");
					break;

				case self::STATE_PLEASE_ENTER_COMMENT:
					$signon_id = $this->getState('SIGNON_ID');
					$model = MCourseScheduleSignon::findOne($signon_id);
					if ($model === null)
						return $this->responseText("此签到ID不存在!\n\n".$this->getEnterSignonIdPrompt()); 
					
					$model->is_signon = 1;
					$model->memo = $msg;
					if ($model->save())
					{
						$mediaId = $this->getState('MEDIA_ID');
						$targetFileId = date("YmdHis").'-'.uniqid();
						$ext = 'jpg';
						$targetFileName = "{$targetFileId}.{$ext}";
						$targetFilePath = "/".MPhoto::PHOTO_PATH."/".$targetFileName;
						$targetFile = Yii::getAlias('@storage').$targetFilePath;
						$this->WxMediaDownload($mediaId, $targetFile);
						MPhoto::thumbnailAll($targetFile);			
				
						$photo = new MPhoto;
						$photo->title =  'Signon';
						$photo->des = $model->student->name;
						$photo->pic_url = $targetFilePath;
						if ($photo->save())
						{						
							$photoOwner = new MPhotoOwner();
							$photoOwner->owner_cat = MPhotoOwner::PHOTO_OWNER_SIGNON;
							$photoOwner->owner_id = $model->student_id;
							$photoOwner->sort_order = 0;
							$photo->savePhotoOwner($photoOwner);	
						}						
					}
				
					$this->setState('STATUS', self::STATE_PLEASE_UPLOAD_IMAGE); 					
					return $this->responseText("签到成功! 请上传下一个签到照片, 0:退出");
					break;

				case self::STATE_PLEASE_UPLOAD_IMAGE:
					return $this->responseText("请先传签到照片, 0:退出");

				default:
					return $this->responseText("无效的状态, 0:退出");														
					
			}
			break;
		}
		return Wechat::NO_RESP;		
	}

    protected function onImage() 
    { 
		$openid = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');	
		$mediaId = $this->getRequest('MediaId');
		$picUrl = $this->getRequest('PicUrl');		
		$state = $this->getState('STATUS');
		if ($state == self::STATE_PLEASE_UPLOAD_IMAGE)		
		{		
			$teacher_id = $this->getState('TEACHER_ID');
			$this->setState('TEACHER_ID', $teacher_id);
			$this->setState('MEDIA_ID', $mediaId);			
			$this->setState('STATUS', self::STATE_PLEASE_ENTER_SIGNON_ID);				
			//U::W("ok, recved your image, mediaId = $mediaId, picUrl=$picUrl");
			return $this->responseText($this->getEnterSignonIdPrompt());
		}
		return Wechat::NO_RESP;
    }
	
    public function FuncNearestOffice() 
    { 
        return Wechat::NO_RESP;    

        $FromUserName = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');
        //$model = MUser::findOne(['gh_id'=>$gh_id, 'openid'=>$FromUserName]);                
        $model = $this->getUser();
        if ($model === null)
            return Wechat::NO_RESP;    
        $sendLocationInfo = $this->getRequest('SendLocationInfo');
        $model->lat = $sendLocationInfo['Location_X'];
        $model->lon = $sendLocationInfo['Location_Y'];        
        //$model->scale = $this->getRequest('Scale');            
        $model->save(false);
        $rows = MOffice::getNearestOffices($gh_id, $model->lon, $model->lat);
        $rows = array_slice($rows, 0, 4);
        $items = [];
        $i = 0;
        foreach ($rows as $row)
        {
            //$url = "http://apis.map.qq.com/uri/v1/routeplan?type=bus&from=我的位置&fromcoord={$model->lat},{$model->lon}&to={$row['title']}&tocoord={$row['lat']},{$row['lon']}&policy=0&referer=wosotech";
            //$url = "http://api.map.baidu.com/direction?origin=latlng:{$model->lat},{$model->lon}|name:我的位置&destination=latlng:{$row['lat']},{$row['lon']}|name:{$row['title']}&mode=driving&region=襄阳&output=html&src=wosotech|wosotech";            
            $office_imgurl = '@web/images/office/'.'office'.$row['office_id'].'.jpg' ;
            $office_imgurl_160 = $office_imgurl.'-160x160.jpg';
            $url = Url::to(['wapx/nearestmap', 'gh_id'=>$gh_id, 'openid'=>$FromUserName, 'office_id'=>$row['office_id'], 'lon'=>$model->lon, 'lat'=>$model->lat], true);
            //$items[] = new RespNewsItem("{$row['title']}({$row['address']}-距离{$row['distance']}米)", $row['title'], Url::to($i == 0 ? '@web/images/nearestoffice-info.jpg' : '@web/images/metro-intro.jpg',true), $url);
            $items[] = new RespNewsItem("{$row['title']}({$row['address']}-距离{$row['distance']}米)", $row['title'], Url::to($i == 0 ? $office_imgurl : $office_imgurl_160, true), $url);
            $i++;
        }
        return $this->responseNews($items);
    }

    protected function onLocation() 
    { 
        return Wechat::NO_RESP;    

        $FromUserName = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');
        $model = MUser::findOne(['gh_id'=>$gh_id, 'openid'=>$FromUserName]);                
        if ($model === null)
            return Wechat::NO_RESP;    
            
        $model->lat = $this->getRequest('Location_X');
        $model->lon = $this->getRequest('Location_Y');
        //$model->scale = $this->getRequest('Scale');            
        $model->save(false);
        $rows = MOffice::getNearestOffices($gh_id, $model->lon, $model->lat);
        $rows = array_slice($rows, 0, 4);
        $items = [];
        $i = 0;
        foreach ($rows as $row)
        {
            //$url = "http://apis.map.qq.com/uri/v1/routeplan?type=bus&from=我的位置&fromcoord={$model->lat},{$model->lon}&to={$row['title']}&tocoord={$row['lat']},{$row['lon']}&policy=0&referer=wosotech";
            //$url = "http://api.map.baidu.com/direction?origin=latlng:{$model->lat},{$model->lon}|name:我的位置&destination=latlng:{$row['lat']},{$row['lon']}|name:{$row['title']}&mode=driving&region=襄阳&output=html&src=wosotech|wosotech";
            
            $office_imgurl = '@web/images/office/'.'office'.$row['office_id'].'.jpg' ;
            $office_imgurl_160 = $office_imgurl.'-160x160.jpg';

            $url = Url::to(['wapx/nearestmap', 'gh_id'=>$gh_id, 'openid'=>$FromUserName, 'office_id'=>$row['office_id'], 'lon'=>$model->lon, 'lat'=>$model->lat], true);
            //$items[] = new RespNewsItem("{$row['title']}({$row['address']}-距离{$row['distance']}米)", $row['title'], Url::to($i == 0 ? '@web/images/nearestoffice-info.jpg' : '@web/images/metro-intro.jpg',true), $url);
            $items[] = new RespNewsItem("{$row['title']}({$row['address']}-距离{$row['distance']}米)", $row['title'], Url::to($i == 0 ? $office_imgurl : $office_imgurl_160, true), $url);
            $i++;
        }
        return $this->responseNews($items);

    }

    protected function onEventLocation()
    { 
        return Wechat::NO_RESP;    
        
        $FromUserName = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');
        $model = MUser::findOne(['gh_id'=>$gh_id, 'openid'=>$FromUserName]);        
        if ($model !== null)
        {
            $model->lat = $this->getRequest('Latitude');
            $model->lon = $this->getRequest('Longitude');
            $model->prec = $this->getRequest('Precision');
            $model->save(false);
            U::W("{$model->lat}, {$model->lon}");            
        }    
        return Wechat::NO_RESP;
    }

    protected function onView() 
    {
        //$this->saveAccessLogAll();
        return parent::onView();    
    }

    protected function onClick()
    {
        //$this->saveAccessLogAll();
        return parent::onClick();
    }

    protected function onScan() 
    {
        return Wechat::NO_RESP;        
    }

    protected function onVoice() 
    {
        return Wechat::NO_RESP;        
    }

    protected function onVideo() 
    {
        return Wechat::NO_RESP;        
    }

    
    public function FuncMyReserve() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		$url = Url::to(['yss/myreserve', 'gh_id'=>$gh_id, 'openid'=>$openid], true);		
		$items = array(
			new RespNewsItem('我要预约', '引进国外先进教育课程体系，课程特别为2岁至12岁的孩子量身定制，让您的孩子与众不同！ 猛戳图文立即预约...', MPhoto::getUploadPicUrl('myreserve.jpg'), $url),
		);
		return $this->responseNews($items);
    }

    public function FuncMyRecommend() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		if (empty($model->openidOwners)) 
			return $this->getBindHint();

		$student_ids = $this->getStudentIds($model->openidOwners);		
		if (empty($student_ids))
			return $this->getBindHint();

		$url = Url::to(['yss/myrecommend', 'gh_id'=>$gh_id, 'openid'=>$openid,'student_ids'=>$student_ids], true);		
		$items = array(
			new RespNewsItem('推荐有礼', '推荐朋友加入爱迪天才大家庭，还有大奖那哟~ 猛戳图文，立即进入推荐有礼...', MPhoto::getUploadPicUrl('myrecommend.jpg'), $url),
		);
		return $this->responseNews($items);
    }


    public function FuncMyCourse() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		if (empty($model->openidOwners)) 
			return $this->getBindHint();	

		$student_ids = $this->getStudentIds($model->openidOwners);		
		if (empty($student_ids))
			return $this->getBindHint();

		$url = Url::to(['yss/mycourse', 'gh_id'=>$gh_id, 'openid'=>$openid,'student_ids'=>$student_ids], true);		
		$items = array(
			new RespNewsItem('宝贝课表', '点击图文查看您宝贝的课程安排...', MPhoto::getUploadPicUrl('mycourse.jpg'), $url),
		);
		return $this->responseNews($items);		
    }
	
    public function FuncMySigon() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		if (empty($model->openidOwners)) 
			return $this->getBindHint();

		$student_ids = $this->getStudentIds($model->openidOwners);
		if (empty($student_ids))
			return $this->getBindHint();

		$url = Url::to(['yss/mysigon', 'gh_id'=>$gh_id, 'openid'=>$openid,'student_ids'=>$student_ids], true);		
		$items = array(
			new RespNewsItem('签到记录', '点击图文查看您宝贝课程签到记录...', MPhoto::getUploadPicUrl('mysigon.jpg'), $url),
		);
		return $this->responseNews($items);		
    }

    public function FuncMyBaby() 
    { 
        $openid = $FromUserName = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');		
        $model = $this->getUser();		
		if (empty($model->openidOwners)) 
			return $this->getBindHint();

		$student_ids = $this->getStudentIds($model->openidOwners);
		if (empty($student_ids))
			return $this->getBindHint();

		foreach ($model->openidOwners as $openidOwner) 
		{
			if ($openidOwner->owner_cat == MOpenidOwner::OPENID_OWNER_STUDENT) 
			{
				$student = MStudent::findOne($openidOwner->owner_id);
				if (empty($student))
				{
					U::W(['Invalid student_id.', __METHOD__, $gh_id, $openid, $openidOwner->getAttributes()]);
					continue;
				}

				$photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_STUDENT, $student->student_id, 1);	
				if (empty($photos))
					continue;

				$photo = $photos[0];
				$url = Url::to(['yss/mybaby', 'gh_id'=>$gh_id, 'openid'=>$openid,'student_ids'=>$student_ids], true);
				$items = array(
					//new RespNewsItem("title {$student->name}", "desc {$student->name}", Url::to($photo->getPicUrl(900, 480), true), $url)					
                    new RespNewsItem("宝贝相册", "在这里,记录了您的宝贝在爱迪天才快乐的学习和成长的点点滴滴。点击进入宝贝相册...", Url::to($photo->getPicUrl(900, 480), true), $url)                   
				);
				return $this->responseNews($items);
			}
		}		
		return $this->responseText("您的宝贝现在还没有任何相片。没关系，赶紧上传几张吧!");
    }
	
    public function FuncMyBind() 
    { 
		return $this->getBindHint();
    }	
	
	protected function getBindHint($to = '') 
	{ 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		$url = Url::to(['yss/mybind', 'gh_id'=>$gh_id, 'openid'=>$openid, 'to'=>$to], true);		
		$items = array(
			new RespNewsItem('绑定管理', '通过绑定管理轻松的建立您和您的宝贝们的对应关系，这样更方便您查看宝贝相册、宝贝课表。猛戳图文进入绑定管理...', MPhoto::getUploadPicUrl('mybind.jpg'), $url),
		);
		
		//return $this->responseText("<a href=\"{$url}\">Please bind first</a>");		
		return $this->responseNews($items); 			   
	}	

	protected function getStudentIds($openidOwners) 
	{ 
		$ids = [];
		foreach ($openidOwners as $openidOwner) 
		{
			if ($openidOwner->owner_cat == MOpenidOwner::OPENID_OWNER_STUDENT) 
			{
				$student = MStudent::findOne($openidOwner->owner_id);
				if (empty($student))
				{
					U::W(['Invalid student_id.', __METHOD__, $gh_id, $openid, $openidOwner->getAttributes()]);
					continue;
				}
				$ids[] = $student->student_id;
			}
		}
		
		return empty($ids) ? '' : implode(':', $ids);
	}	

}

/*					
					$model->is_signon = 1;
					if ($model->save())
					{
						$mediaId = $this->getState('MEDIA_ID');
						$targetFileId = date("YmdHis").'-'.uniqid();
						$ext = 'jpg';
						$targetFileName = "{$targetFileId}.{$ext}";
						$targetFilePath = "/".MPhoto::PHOTO_PATH."/".$targetFileName;
						$targetFile = Yii::getAlias('@storage').$targetFilePath;
						$this->WxMediaDownload($mediaId, $targetFile);
						MPhoto::thumbnailAll($targetFile);			

						$photo = new MPhoto;
						$photo->title =  'Signon';
						$photo->des = $model->student->name;
						$photo->pic_url = $targetFilePath;
						if ($photo->save())
						{						
							$photoOwner = new MPhotoOwner();
							$photoOwner->owner_cat = MPhotoOwner::PHOTO_OWNER_SIGNON;
							$photoOwner->owner_id = $model->student_id;
							$photoOwner->sort_order = 0;
							$photo->savePhotoOwner($photoOwner);	
						}
						
					}

	protected function onText() 
    { 
      //$this->saveAccessLogAll();
        $openid = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');    
        $Content = $this->getRequest('Content');
        $msg = trim($Content);   

        if ($msg == '.debug')
        {
            $url = Url::to(['wap/testpay', 'gh_id'=>$gh_id, 'openid'=>$openid, 'owner'=>1], true);
            return $this->responseText("Test only <a href=\"{$url}\">clickme</a>\n----------\n <a href=\"http://m.wsq.qq.com/263163652\">wsq</a>    \n----------\n  <a href=\"http://www.baidu.com/?surf_token=a40aeb590b4674cad5c74246ba41bd9f\">active wifi</a>");
        }

		$model = MUser::findOne(['gh_id'=>$gh_id, 'openid'=>$openid]);
		$nickname = empty($model->nickname) ? '' : $model->nickname;            
		return $this->responseText("{$nickname}, 您好, 欢迎进入官方微信服务号...! ");
    }
*/

