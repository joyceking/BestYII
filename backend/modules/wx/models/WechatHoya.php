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
use common\models\MKeyword;
use common\models\MSchoolBranch;
use common\models\MWxAction;

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
		if (empty($courseSchedules)) {
			$this->deleteStatusAll();
			return "今天无课,不用签到 ^_^\n,";
		}
		$signon_ids = [];
		//$str = "请选择一个签到ID\n";
		$str = "";
		foreach($courseSchedules as $courseSchedule)
		{
			$str .= "{$courseSchedule->courseUnit->course->title} {$courseSchedule->courseUnit->title} \n";
			$signons = $courseSchedule->courseScheduleSignons;
			foreach($signons as $signon)
			{
				$str .= "{$signon->signon_id}. {$signon->student->name} \n";
				$signon_ids[] = $signon->signon_id;
			}
		}
		$str .= "请输入序号,按0退出";
		$this->setState('SIGNON_RANGE_IDS', empty($signon_ids)?'':implode(':', $signon_ids));		
		return $str;		
	}

	protected function deleteStatusAll() 
	{ 
		$this->deleteState('STATUS');
		$this->deleteState('TEACHER_ID');
		$this->deleteState('MEDIA_ID');								
		$this->deleteState('SIGNON_RANGE_IDS');												
		$this->deleteState('SIGNON_ID');																
	}

	protected function handleKeyword($keyword)
	{
		$gh_id = $this->getRequest('ToUserName');
		$model = MWxAction::findOne(['gh_id'=>$gh_id, 'keyword'=>$keyword]);
		if ($model === null) {
			return false;
		}
		return $model->getResp($this);
	}

	protected function onText() 
	{
		$openid = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');
		$Content = $this->getRequest('Content');
		if (($resp = $this->handleKeyword($Content)) !== false) {
			return $resp;
		}
		while(1)
		{
			//$Content = $this->getRequest('Content');
			$msg = trim($Content);	
			$state = $this->getState('STATUS');
			if ($msg == '0' && $state != self::STATE_NONE)
			{
				$this->deleteStatusAll();
				return $this->responseText("谢谢使用, 再见");
			}

			//U::W($state);
			switch ($state) 
			{
				case self::STATE_NONE:
					if ($msg == '签到') 
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
								$url = Url::to(['yss/signlist', 'gh_id'=>$gh_id, 'openid'=>$openid, 'teacher_id'=>$teacher->teacher_id], true);		
								return $this->responseText("<a href=\"{$url}\">点此链接为学生签到</a>");
							}
						}		
						return $this->getBindHint();
					}

					if ($msg == '签到照片')
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
								return $this->responseText("请上传签到照片,按0退出");
							}
						}		
						return $this->getBindHint();
					}
					if($msg == '宝宝相册'){
                            $model = $this->getUser();
						if (empty($model->openidOwners)) {
                                                    return $this->getBindHint();
                                                }else{
                                                    return $this->FuncMyBaby();
                                                }		
                                        }
                                        if($msg == '宝宝课表'){
                                            $model = $this->getUser();
						if (empty($model->openidOwners)) {
                                                    return $this->getBindHint();
                                                }else{
                                                    return $this->FuncMyCourse();
                                                }		
                                        }  
                                        if($msg == '签到记录'){
                                            $model = $this->getUser();
						if (empty($model->openidOwners)) {
                                                    return $this->getBindHint();
                                                }else{
                                                    return $this->FuncMySigon();
                                                }		
                                        }  
                                        if($msg == '绑定管理'){
                                            $model = $this->getUser();
						if (empty($model->openidOwners)) {
                                                    return $this->getBindHint();
                                                }else{
                                                    return $this->FuncMyBind();
                                                }		
                                        }  
                        if($msg == '校区地图'){
                                            return $this->FuncMySchoolbranch();	
                                        }  
					break;
					
				case self::STATE_PLEASE_ENTER_SIGNON_ID:
					if (!is_numeric($msg))
						return $this->responseText("无效的序号.\n\n".$this->getEnterSignonIdPrompt()); 

					$signon_ids = $this->getState('SIGNON_RANGE_IDS');
					if (empty($signon_ids)) {
						$this->deleteStatusAll();
						return $this->responseText("今天无课,不用签到,谢谢使用,再见"); 
					}
					
					$signon_ids = explode(':', $signon_ids);
					if (!in_array($msg, $signon_ids))
						return $this->responseText("无效的序号\n\n".$this->getEnterSignonIdPrompt()); 
					
					$model = MCourseScheduleSignon::findOne($msg);
					if ($model === null)
						return $this->responseText("此序号不存在\n\n".$this->getEnterSignonIdPrompt()); 
					$this->setState('SIGNON_ID', $msg);
					$this->setState('STATUS', self::STATE_PLEASE_ENTER_COMMENT); 					
					return $this->responseText("请输入评语,按0退出");
					break;

				case self::STATE_PLEASE_ENTER_COMMENT:
					$signon_id = $this->getState('SIGNON_ID');
					$model = MCourseScheduleSignon::findOne($signon_id);
					if ($model === null)
						return $this->responseText("此序号不存在!\n\n".$this->getEnterSignonIdPrompt()); 
					
					$model->is_signon = 1;
					$model->memo = $msg;
					if ($model->save())
					{
						$mediaId = $this->getState('MEDIA_ID');
						$targetFileId = date("YmdHis").'-'.uniqid();
						$ext = 'jpg';
						$targetFileName = "{$targetFileId}.{$ext}";
						$targetFile = Yii::getAlias('@storage') . DIRECTORY_SEPARATOR . MPhoto::PHOTO_PATH . DIRECTORY_SEPARATOR . $targetFileName;						
						$this->WxMediaDownload($mediaId, $targetFile);
						//MPhoto::thumbnailAll($targetFile);			
				
						$photo = new MPhoto;
						$photo->title =  'Signon';
						$photo->des = $model->student->name;
						$photo->pic_url = $targetFileName;
						
						if ($photo->save())
						{						
							$photoOwner = new MPhotoOwner();
							$photoOwner->owner_cat = MPhotoOwner::PHOTO_OWNER_SIGNON;
							$photoOwner->owner_id = $signon_id;
							$photoOwner->sort_order = 0;
							$photo->savePhotoOwner($photoOwner);

							$photoOwner = new MPhotoOwner();
							$photoOwner->owner_cat = MPhotoOwner::PHOTO_OWNER_STUDENT;
							$photoOwner->owner_id = $model->student_id;
							$photoOwner->sort_order = 0;
							$photo->savePhotoOwner($photoOwner);
						}						
					}
				
					$this->setState('STATUS', self::STATE_PLEASE_UPLOAD_IMAGE); 					
					return $this->responseText("保存成功! 请上传下一个签到照片,按0退出");
					break;

				case self::STATE_PLEASE_UPLOAD_IMAGE:
					return $this->responseText("请先上传签到照片,按0退出");

				default:
					return $this->responseText("无效的状态,按0退出");														
					
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
		return $this->responseText("System panic!");
    }

    protected function onLocation() 
    { 
        $FromUserName = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');
        $model = MUser::findOne(['gh_id'=>$gh_id, 'openid'=>$FromUserName]); 
        if ($model === null) {
            return Wechat::NO_RESP;    
        }
            
        $model->lat = $this->getRequest('Location_X');
        $model->lon = $this->getRequest('Location_Y');
        $model->save(false);
		return $this->getNearestSchoolBranchNews($model->lon, $model->lat);
    }

    protected function onEventLocation()
    { 
        return Wechat::NO_RESP;    
        
        $FromUserName = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');
        $model = MUser::findOne(['gh_id'=>$gh_id, 'openid'=>$FromUserName]);        
        if ($model !== null) {
            $model->lat = $this->getRequest('Latitude');
            $model->lon = $this->getRequest('Longitude');
            $model->prec = $this->getRequest('Precision');
            $model->save(false);
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
		$Content = $this->getRequest('EventKey');
		if (($resp = $this->handleKeyword($Content)) !== false) {
			return $resp;
		}
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
	
    public function FuncMyCourses() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		//if (empty($model->openidOwners)) 
		//	return $this->getBindHint();	

		$student_ids = $this->getStudentIds($model->openidOwners);		
		//if (empty($student_ids))
		//	return $this->getBindHint();

		$url = Url::to(['yss/course', 'gh_id'=>$gh_id, 'openid'=>$openid,'student_ids'=>$student_ids], true);		
		$items = array(
			new RespNewsItem('课程介绍', '点击图文进入课程介绍...', MPhoto::getUploadPicUrl('kcjs-tw.jpg'), $url),
		);
		return $this->responseNews($items);		
    }
	
    private function getNearestSchoolBranchNews($lon, $lat) 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
        $rows = MSchoolBranch::getNearestSchoolBranch($gh_id, $lon, $lat); 
        $rows = array_slice($rows, 0, 4);
        $items = [];
        $i = 0;
        foreach ($rows as $row)
        {
        	U::W('sssssssssssssssssssssss');
            //$office_imgurl = MPhoto::getUploadPicUrl('teacher.jpg') ;
            //$office_imgurl_160 = MPhoto::getUploadPicUrl('teacher.jpg');

            $model = MSchoolBranch::findOne($row['school_branch_id']);
            U::W($model);
            $photo = $model->getPhoto('');
            $office_imgurl = $photo->getPicUrl();
            $office_imgurl_160 = $photo->getPicUrl(160,160);

            $url = Url::to(['yss/schoolbranchshowdetail', 'gh_id'=>$gh_id, 'openid'=>$FromUserName, 'school_branch_id'=>$row['school_branch_id'], 'lon'=>$lon, 'lat'=>$lat], true);
			$row['des'] = strip_tags($row['des']);
            $items[] = new RespNewsItem("{$row['des']}-距离{$row['distance']}米", $row['title'], Url::to($i == 0 ? $office_imgurl : $office_imgurl_160, true), $url);
            $i++;
        }
        return $this->responseNews($items);
	}

	/*
    public function FuncMySchoolbranch() 
    { 
        $FromUserName = $this->getRequest('FromUserName');
        $gh_id = $this->getRequest('ToUserName');
        $model = $this->getUser();
        if ($model === null)
            return Wechat::NO_RESP;    
        $sendLocationInfo = $this->getRequest('SendLocationInfo');
        $model->lat = $sendLocationInfo['Location_X'];
        $model->lon = $sendLocationInfo['Location_Y'];        
        $model->save(false);
		return $this->getNearestSchoolBranchNews($model->lon, $model->lat);
    }
    */


    public function FuncMySchoolbranch() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		
		//if (empty($model->openidOwners)) 
		//	return $this->getBindHint();	

		//$student_ids = $this->getStudentIds($model->openidOwners);		
		//if (empty($student_ids))
		//	return $this->getBindHint();

		//$url = Url::to(['yss/schoolbranch', 'gh_id'=>$gh_id, 'openid'=>$openid], true);
		$url = Url::to(['yss/schoolregion', 'gh_id'=>$gh_id, 'openid'=>$openid], true);
		$items = array(
			new RespNewsItem('校区查询', '点击图文进入校区查询...', MPhoto::getUploadPicUrl('xqcx-tw.jpg'), $url),
		);
		return $this->responseNews($items);		
    }


    public function FuncMyTeachershow() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		
		//if (empty($model->openidOwners)) 
		//	return $this->getBindHint();	

		//$student_ids = $this->getStudentIds($model->openidOwners);		
		//if (empty($student_ids))
		//	return $this->getBindHint();

		$url = Url::to(['yss/teachershow', 'gh_id'=>$gh_id, 'openid'=>$openid], true);		
		$items = array(
			new RespNewsItem('教师风采', '点击图文进入教师风采...', MPhoto::getUploadPicUrl('jsfc-tw.jpg'), $url),
		);
		return $this->responseNews($items);		
    }
	
    public function FuncMyStudentshow() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		
		//if (empty($model->openidOwners)) 
		//	return $this->getBindHint();	

		//$student_ids = $this->getStudentIds($model->openidOwners);		
		//if (empty($student_ids))
		//	return $this->getBindHint();

		$url = Url::to(['yss/studentshow', 'gh_id'=>$gh_id, 'openid'=>$openid], true);		
		$items = array(
			new RespNewsItem('爱迪宝贝秀', '点击图文进入爱迪宝贝秀...', MPhoto::getUploadPicUrl('adbbx-tw.jpg'), $url),
		);
		return $this->responseNews($items);		
    }
	
	
    public function FuncMyAdabout() 
    { 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		//if (empty($model->openidOwners)) 
		//	return $this->getBindHint();	

		//$student_ids = $this->getStudentIds($model->openidOwners);		
		//if (empty($student_ids))
		//	return $this->getBindHint();

		$url = Url::to(['yss/adabout', 'gh_id'=>$gh_id, 'openid'=>$openid], true);		
		$items = array(
			new RespNewsItem('关于爱迪', '点击图文进入关于爱迪...', MPhoto::getUploadPicUrl('gyad-tw.jpg'), $url),
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
			new RespNewsItem('签到记录', '点击图文查看您宝贝课程签到记录...', MPhoto::getUploadPicUrl('qdjl-tw.jpg'), $url),
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
                    new RespNewsItem("宝贝相册", "在这里,记录了您的宝贝在爱迪天才快乐的学习和成长的点点滴滴。点击进入宝贝相册...", Url::to($photo->getPicUrl(900, 480), true), $url),                 
                    //new RespNewsItem("宝贝相册", "在这里,记录了您的宝贝在爱迪天才快乐的学习和成长的点点滴滴。点击进入宝贝相册...", MPhoto::getUploadPicUrl('bbxc-tw.jpg'), true), $url)                   
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

    public function FuncMyActivity() 
    {
		$key = MKeyword::findOne(['keyword'=>'api_1_key']);
		$apiurl = MKeyword::findOne(['keyword'=>'api_1_apiurl']);
		$token = MKeyword::findOne(['keyword'=>'api_1_token']);
		return $this->procRemote(['apiurl'=>$apiurl->value, 'key'=>$key->value, 'token'=>$token->value]);
    }
	
	protected function getBindHint($to = '') 
	{ 
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		$model = $this->getUser();
		$url = Url::to(['yss/mybind', 'gh_id'=>$gh_id, 'openid'=>$openid, 't'=>$to], true);
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

	public function strexists($string, $find) {
		return !(strpos($string, $find) === false);
	}

	public function procRemote($item) {
		$openid = $FromUserName = $this->getRequest('FromUserName');
		$gh_id = $this->getRequest('ToUserName');		
		//$Content = $this->getRequest('Content');
		$MsgType = $this->getRequest('MsgType');
		$Content = $item['key'];
		U::W([$item, $Content]);

		$TIMESTAMP = time();
		//load()->func('communication');
		if (!$this->strexists($item['apiurl'], '?')) {
			$item['apiurl'] .= '?';
		} else {
			$item['apiurl'] .= '&';
		}
		
		$sign = array(
			'timestamp' => $TIMESTAMP,
			//'nonce' => \random(10, 1),
			'nonce' => rand(1, 10000),			
		);
		$signkey = array($item['token'], $sign['timestamp'], $sign['nonce']);
		sort($signkey, SORT_STRING);
		$sign['signature'] = sha1(implode($signkey));
		$item['apiurl'] .= http_build_query($sign, '', '&');
		
		//if($this->message['type'] == 'text') 
		if ($MsgType == 'text') 
		{
			$body = $GLOBALS["HTTP_RAW_POST_DATA"];
		} 
		else 
		{
/*		
			$body = "<xml>" . PHP_EOL .
					"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
					"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
					"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
					"<MsgType><![CDATA[text]]></MsgType>" . PHP_EOL .
					"<Content><![CDATA[{$this->message['content']}]]></Content>" . PHP_EOL .
					"<MsgId>".$TIMESTAMP."</MsgId>" . PHP_EOL .
					"</xml>";
*/
			$body = "<xml>" . PHP_EOL .
				"<ToUserName><![CDATA[{$gh_id}]]></ToUserName>" . PHP_EOL .
				"<FromUserName><![CDATA[{$openid}]]></FromUserName>" . PHP_EOL .
				"<CreateTime>1402545118</CreateTime>" . PHP_EOL .
				"<MsgType><![CDATA[text]]></MsgType>" . PHP_EOL .
				"<Content><![CDATA[{$Content}]]></Content>" . PHP_EOL .
				"<MsgId>".$TIMESTAMP."</MsgId>" . PHP_EOL .
				"</xml>";
			
		}

		//U::W($body);
		$response = $this->ihttp_request($item['apiurl'], $body, array('CURLOPT_HTTPHEADER' => array('Content-Type: text/xml; charset=utf-8')));
		//U::W($response);

		$result = array();
		if (!$this->is_error($response)) {
			$temp = @json_decode($response['content'], true);
			if (is_array($temp)) {
				U::W(['respone is array....', __METHOD__, $temp]);				
				//$result = $this->buildResponse($temp);
			} else {
				if (!empty($response['content'])) {
					$obj = @simplexml_load_string(trim($response['content']), 'SimpleXMLElement', LIBXML_NOCDATA);
					$obj = json_decode(json_encode($obj));
					//U::W($obj);
					//if($obj instanceof SimpleXMLElement) 
					if (1)
					{
						
						$type = strtolower(strval($obj->MsgType));
						if($type == 'text') {
							//$result = $this->respText(strval($obj->Content));
							return $this->responseText(strval($obj->Content));
						}

						if($type == 'news') {
							$news = array();
							if (is_array($obj->Articles->item))
							{
								$items = $obj->Articles->item;
								foreach($items as $item) {
									//U::W($item);
									$news[] = new RespNewsItem(strval($item->Title), strval($item->Description), strval($item->PicUrl), strval($item->Url));
								}
							}
							else
							{
								$item = $obj->Articles->item;
								$news[] = new RespNewsItem(strval($item->Title), strval($item->Description), strval($item->PicUrl), strval($item->Url));							
							}
							return $this->responseNews($news);
						}
						
/*						
						if($type == 'text') {
							$result = $this->respText(strval($obj->Content));
						}
						if($type == 'image') {
							$imid = strval($obj->Image->MediaId);
							$result = $this->respImage($imid);
						}
						if($type == 'voice') {
							$imid = strval($obj->Voice->MediaId);
							$result = $this->respVoice($imid);
						}
						if($type == 'video') {
							$video = array();
							$video['video'] = strval($obj->Video->MediaId);
							$video['thumb'] = strval($obj->Video->ThumbMediaId);
							$result = $this->respVideo($video);
						}
						if($type == 'music') {
							$music = array();
							$music['title'] = strval($obj->Music->Title);
							$music['description'] = strval($obj->Music->Description);
							$music['musicurl'] = strval($obj->Music->MusicUrl);
							$music['hqmusicurl'] = strval($obj->Music->HQMusicUrl);
							$result = $this->respMusic($music);
						}
						if($type == 'news') {
							$news = array();
							foreach($obj->Articles->item as $item) {
								$news[] = array(
									'title' => strval($item->Title),
									'description' => strval($item->Description),
									'picurl' => strval($item->PicUrl),
									'url' => strval($item->Url)
								);
							}
							$result = $this->respNews($news);
						}
*/						
					}
				}
				else
					U::W(['content is empty...', $response]);												
			}

/*			
			if(@stristr($result, '{begin-context}') !== false) {
				$this->beginContext(0);
				$result = str_ireplace('{begin-context}', '', $result);
			}
			if(@stristr($result, '{end-context}') !== false) {
				$this->endContext();
				$result = str_ireplace('{end-context}', '', $result);
			}
*/			
			//return $result;
			return '';
		} else {
			U::W('ihttp_request error');
			return '';
		}
	}


	public function ihttp_request($url, $post = '', $extra = array(), $timeout = 60) {
		//U::W($url);		
		//U::W($post); 	

		$urlset = parse_url($url);
		if(empty($urlset['path'])) {
			$urlset['path'] = '/';
		}
		if(!empty($urlset['query'])) {
			$urlset['query'] = "?{$urlset['query']}";
		}
		if(empty($urlset['port'])) {
			$urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
		}
		if ($this->strexists($url, 'https://') && !extension_loaded('openssl')) {
			if (!extension_loaded("openssl")) {
				U::W('has no openssl');
			}
		}
		if(function_exists('curl_init') && function_exists('curl_exec')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlset['scheme']. '://' .$urlset['host'].($urlset['port'] == '80' ? '' : ':'.$urlset['port']).$urlset['path'].$urlset['query']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			if($post) {
				curl_setopt($ch, CURLOPT_POST, 1);
				if (is_array($post)) {
					$post = http_build_query($post);
				}
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
			if (!empty($extra) && is_array($extra)) {
				$headers = array();
				foreach ($extra as $opt => $value) {
					if ($this->strexists($opt, 'CURLOPT_')) {
						curl_setopt($ch, constant($opt), $value);
					} elseif (is_numeric($opt)) {
						curl_setopt($ch, $opt, $value);
					} else {
						$headers[] = "{$opt}: {$value}";
					}
				}
				if(!empty($headers)) {
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				}
			}
			
			$data = curl_exec($ch);
			$status = curl_getinfo($ch);
			$errno = curl_errno($ch);
			$error = curl_error($ch);
			curl_close($ch);
			if($errno || empty($data)) {
				return error(1, $error);
			} else {
			
					U::W('why???'); 
				U::W($data); 	
				return $this->ihttp_response_parse($data);
			}
		}
		$method = empty($post) ? 'GET' : 'POST';
		$fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
		$fdata .= "Host: {$urlset['host']}\r\n";
		if(function_exists('gzdecode')) {
			$fdata .= "Accept-Encoding: gzip, deflate\r\n";
		}
		$fdata .= "Connection: close\r\n";
		if (!empty($extra) && is_array($extra)) {
			foreach ($extra as $opt => $value) {
				if (!$this->strexists($opt, 'CURLOPT_')) {
					$fdata .= "{$opt}: {$value}\r\n";
				}
			}
		}
		$body = '';
		if ($post) {
			if (is_array($post)) {
				$body = http_build_query($post);
			} else {
				$body = urlencode($post);
			}
			$fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
		} else {
			$fdata .= "\r\n";
		}
		if($urlset['scheme'] == 'https') {
			$fp = fsockopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
		} else {
			$fp = fsockopen($urlset['host'], $urlset['port'], $errno, $error);
		}
		stream_set_blocking($fp, true);
		stream_set_timeout($fp, $timeout);
		if (!$fp) {
			return error(1, $error);
		} else {
			fwrite($fp, $fdata);
			$content = '';
			while (!feof($fp))
				$content .= fgets($fp, 512);
			fclose($fp);
			return ihttp_response_parse($content, true);
		}
	}


	public function ihttp_response_parse($data, $chunked = false) {
		$rlt = array();
		$pos = strpos($data, "\r\n\r\n");
		$split1[0] = substr($data, 0, $pos);
		$split1[1] = substr($data, $pos + 4, strlen($data));
		
		$split2 = explode("\r\n", $split1[0], 2);
		preg_match('/^(\S+) (\S+) (\S+)$/', $split2[0], $matches);
		$rlt['code'] = $matches[2];
		$rlt['status'] = $matches[3];
		$rlt['responseline'] = $split2[0];
		//hehb begin
		$rlt['headers'] = [];
		//end
		$header = explode("\r\n", $split2[1]);
		$isgzip = false;
		$ischunk = false;
		foreach ($header as $v) {
			$row = explode(':', $v);
			$key = trim($row[0]);
			$value = trim($row[1]);
			if ((!empty($rlt['headers'][$key]))&&is_array($rlt['headers'][$key])) {
				$rlt['headers'][$key][] = $value;
			} elseif (!empty($rlt['headers'][$key])) {
				$temp = $rlt['headers'][$key];
				unset($rlt['headers'][$key]);
				$rlt['headers'][$key][] = $temp;
				$rlt['headers'][$key][] = $value;
			} else {
				$rlt['headers'][$key] = $value;
			}
			if(!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
				$isgzip = true;
			}
			if(!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
				$ischunk = true;
			}
		}
		if($chunked && $ischunk) {
			$rlt['content'] = ihttp_response_parse_unchunk($split1[1]);
		} else {
			$rlt['content'] = $split1[1];
		}
		if($isgzip && function_exists('gzdecode')) {
			$rlt['content'] = gzdecode($rlt['content']);
		}

		$rlt['meta'] = $data;
		if($rlt['code'] == '100') {
			return ihttp_response_parse($rlt['content']);
		}
		return $rlt;
	}

	public function ihttp_response_parse_unchunk($str = null) {
		if(!is_string($str) or strlen($str) < 1) {
			return false; 
		}
		$eol = "\r\n";
		$add = strlen($eol);
		$tmp = $str;
		$str = '';
		do {
			$tmp = ltrim($tmp);
			$pos = strpos($tmp, $eol);
			if($pos === false) {
				return false;
			}
			$len = hexdec(substr($tmp, 0, $pos));
			if(!is_numeric($len) or $len < 0) {
				return false;
			}
			$str .= substr($tmp, ($pos + $add), $len);
			$tmp  = substr($tmp, ($len + $pos + $add));
			$check = trim($tmp);
		} while(!empty($check));
		unset($tmp);
		return $str;
	}


	public function ihttp_get($url) {
		return ihttp_request($url);
	}


	public function ihttp_post($url, $data) {
		$headers = array('Content-Type' => 'application/x-www-form-urlencoded');
		return ihttp_request($url, $data, $headers);
	}

	public function is_error($data) {
		if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || (array_key_exists('errno', $data) && $data['errno'] == 0)) {
			return false;
		} else {
			return true;
		}
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

