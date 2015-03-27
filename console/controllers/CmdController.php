<?php

/*
C:\xampp\php\php.exe C:\htdocs\yss\yii cache/flush
C:\xampp\php\php.exe C:\htdocs\yss\yii cmd
C:\xampp\php\php.exe C:\htdocs\yss\yii cmd/create-menu
php yii cmd/create-menu

*/

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\imagine\Image;

use common\models\MPhoto;

use backend\modules\wx\models\U;
use backend\modules\wx\models\MGh;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\MMobnum;
use backend\modules\wx\models\MItem;
use backend\modules\wx\models\MSmQueue;

use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\MOffice;
use backend\modules\wx\models\MStaff;
use backend\modules\wx\models\MGroup;
use backend\modules\wx\models\MChannel;

use backend\modules\wx\models\sm\ESms;
use backend\modules\wx\models\sm\ESmsGuodu;

class CmdController extends Controller
{
    public function init()
    {        
        Yii::$app->getUrlManager()->setBaseUrl('/yss/backend/web/index.php');
        Yii::$app->getUrlManager()->setHostInfo('http://hoyatech.net');
        Yii::$app->getUrlManager()->setScriptUrl('/yss/backend/web/index.php');

        Yii::$app->wx->setGhId(MGh::GH_HOYA);
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd
    public function actionIndex()
    {        
        echo 'Hello, world!!';
    }


    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/create-channel-qrs
    public function actionCreateChannelQrs()
    {        
        $gh_id = Yii::$app->wx->getGhid();
        $file = Yii::$app->getRuntimePath().DIRECTORY_SEPARATOR.'channel_names.txt';
        $fh = fopen($file, "r");
        $i = 0;
        $scene_ids = array();
        while (!feof($fh)) 
        {
            $line = fgets($fh);
            if (empty($line))
                continue;

            $title = trim($line);
            $model = MChannel::findOne(['gh_id'=>$gh_id, 'title'=>$title]);    
            if ($model !== null)
                continue;
                
            $model = new MChannel;
            $model->gh_id = $gh_id;
            $model->title = $title;
            $url = $model->getQrImageUrl();
            
            $i++;
//            if ($i > 2) break;
        }
        fclose($fh);
    }    


    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/get-kf-status
    public function actionGetKfStatus()
    {
        $arr = Yii::$app->wx->WxGetOnlineKfList();
        U::W($arr);
        print_r($arr);
    } 


    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/create-wx-groups
    public function actionCreateWxGroups()
    {        
        $gh_id = Yii::$app->wx->getGhid();
        $offices = MOffice::find()->where("gh_id = :gh_id AND visable = :visable", [':gh_id'=>$gh_id, ':visable'=>1])->asArray()->all();        

        foreach($offices as $office)
        {
            $office_id = $office['office_id'];
            $gname = mb_substr($office['title'], 0, 10, 'utf-8');

            $arr = Yii::$app->wx->WxGroupCreate(['group'=>['name'=>$gname]]);
            //U::W("{$arr['group']['id']},{$arr['group']['name']}");        

            $tableName = MGroup::tableName();
            Yii::$app->db->createCommand("INSERT INTO $tableName (gh_id, gid, gname, office_id) VALUES (:gh_id, :gid, :gname, :office_id)", [':gh_id' => $gh_id, ':gid' => $arr['group']['id'],':gname' => $gname,':office_id' => $office_id])->execute();
        }

    }    
    
    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/get-ad-url
    public function actionGetAdUrl()
    {        
        $gh_id = Yii::$app->wx->getGhid();     
        //$url = Yii::$app->wx->WxGetOauth2Url('snsapi_base', "wap/mobile:{$gh_id}:cid=324");
        $url = Yii::$app->wx->WxGetOauth2Url('snsapi_base', "wap/mobilelist:{$gh_id}");
        U::W($url);
        echo $url;
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/sendsm
    public function actionSendsm()    
    {        
        $mobile = '15527766232';
        if (!U::mobileIsValid($mobile))
        {
            echo "$mobile is a invalid mobile number!";
            return;
        }
        U::W("balance before is ".\backend\modules\wx\models\sm\ESmsGuodu::B());
        //$s = Yii::$app->sm->S($mobile, 'hello world', '', 'guodu', true);
        //$s = Yii::$app->sm->S($mobile, 'hello world', '', null, true);
        $s = Yii::$app->sm->S($mobile, '【襄阳联通】, 您已订购商品', '', null, true);
        U::W($s->resp);
        
        $err_code = $s->getErrorMsg();
        $className = get_class($s);                
        $err_code .= get_class($s);
        
        $smQueue = new MSmQueue;
        $smQueue->gh_id = '123';
        $smQueue->receiver_mobile = $mobile;
        $smQueue->msg = 'hello jack';
        $smQueue->err_code = $err_code;
        if ($s->isSendOk())
        {
            U::W('Send OK');
            $smQueue->status = MSmQueue::STATUS_SENT;
        }
        else 
        {
            U::W('Send ERR');
            $smQueue->status = MSmQueue::STATUS_ERR;            
        }
        $smQueue->save(false);
            
        U::W("balance after is ".\backend\modules\wx\models\sm\ESmsGuodu::B());        
        echo 'Hello, world!!';
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/group-list
    public function actionGroupList()
    {            
/*        
        $arr = Yii::$app->wx->WxGroupCreate(['group'=>['name'=>'Test']]);
        U::W("{$arr['group']['id']},{$arr['group']['name']}");        

        $arr = Yii::$app->wx->WxGroupUpdate(['group'=>['id'=>100, 'name'=>'vip']]);
        U::W($arr);
*/
        $arr = Yii::$app->wx->WxGroupMoveMember(MGh::GH_XIANGYANGUNICOM_OPENID_HBHE, 103);
        U::W($arr);
        
        $arr = Yii::$app->wx->WxGroupListGet();
        U::W($arr);
        return;        
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/get-subscriber-list
    public function actionGetSubscriberList()
    {        
        $openids = Yii::$app->wx->WxGetSubscriberList();
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/qr-image
    public function actionQrImage()
    {    

        $scene_id = 't1';
        $arr = Yii::$app->wx->WxgetQRCode($scene_id);
        //$arr = Yii::$app->wx->WxgetQRCode('weixin://wxpay/bizpayurl?appid=wx79c2bf0249ede62a&noncestr=Vs7Roypb122HLZCh&productid=1234&sign=1ae0ca345323847ec8684254535c1157522e8e02&timestamp=1405751645');
        U::W($arr);
        $url = Yii::$app->wx->WxGetQRUrl($arr['ticket']);
        U::W($url);
        $log_file_path = Yii::$app->getRuntimePath().DIRECTORY_SEPARATOR.'qr'.DIRECTORY_SEPARATOR."$scene_id.jpg";
        U::W($log_file_path);
        Wechat::downloadFile($url, $log_file_path);    
    }
    
    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/message-custom-send
    public function actionMessageCustomSend()
    {    
        //$msg = ['touser'=>MGh::GH_XIANGYANGUNICOM_OPENID_HBHE, 'msgtype'=>'text', 'text'=>['content'=>'您好, 何华斌']];
        //$msg = ['touser'=>MGh::GH_XIANGYANGUNICOM_OPENID_HBHE, 'msgtype'=>'image', 'image'=>['media_id'=>'id123456']];
        //$msg = ['touser'=>MGh::GH_XIANGYANGUNICOM_OPENID_HBHE, 'msgtype'=>'voice', 'voice'=>['media_id'=>'id123456']];        
        //$msg = ['touser'=>MGh::GH_XIANGYANGUNICOM_OPENID_HBHE, 'msgtype'=>'video', 'video'=>['media_id'=>'id123456','title'=>'a', 'description'=>'b']];                
        //$msg = ['touser'=>'oySODt2YXO_JMcFWpFO5wyuEYX-0', 'msgtype'=>'music', 'music'=>['musicurl '=>'http://baidu.com', 'hqmusicurl'=>'', 'thumb_media_id'=>'123', 'title'=>'a', 'description'=>'123']];
        $msg = [
            'touser'=>MGh::GH_XIANGYANGUNICOM_OPENID_HBHE, 
            'msgtype'=>'news', 
            'news'=> [
                'articles'=>[
                    ['title'=>'标题-A', 'description'=>'详情-A, Analyze UA. This tool was developed for user agent string analysis. Our analysis gives you information on client SW type (browser, webcrawler, anonymizer etc.)', 'url'=>'http://hoyatech.net/wx/web/index.php', 'picurl'=>'http://hoyatech.net/wx/web/images/earth.jpg'],
                    ['title'=>'title-b', 'description'=>'description-b, welcome to wechat site', 'url'=>'http://hoyatech.net/wx/web/index.php', 'picurl'=>'http://hoyatech.net/wx/web/images/earth.jpg'],
                ]                
            ]
        ];
        $msg = ['touser'=>MGh::GH_XIANGYANGUNICOM_OPENID_HBHE, 'msgtype'=>'text', 'text'=>['content'=>'how are you, huabin']];
        $arr = Yii::$app->wx->WxMessageCustomSend($msg);
        U::W($arr);        
        return;    
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/media-upload
    public function actionMediaUpload()
    {    
        $arr = Yii::$app->wx->WxMediaUpload('image', 'C:\\earth.jpg');
        //U::W([$arr['type'],$arr['media_id'],$arr['created_at']]);
        return;    
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/media-download
    public function actionMediaDownload()
    {    
        //$url = Yii::$app->wx->WxMediaGetUrl('r2zUx5VVXPVclkPSUTNE3P51dAEZOe8U0UwJCCWZZxSr5UW_SqMmeUODxtjeSnZt');
        //U::W($url);        
        Yii::$app->wx->WxMediaDownload('r2zUx5VVXPVclkPSUTNE3P51dAEZOe8U0UwJCCWZZxSr5UW_SqMmeUODxtjeSnZt', 'abcd.jpg');
        return;    
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/user-info
    public function actionUserInfo()
    {    
//        $arr = Yii::$app->wx->WxGetUserInfo(MGh::GH_XIANGYANGUNICOM_OPENID_HBHE);    
        Yii::$app->wx->setGhId(MGh::GH_HOYA);
        $arr = Yii::$app->wx->WxGetUserInfo(MGh::GH_HOYA_OPENID_HBHE);    
        U::W($arr);
        return;        
    }
    
    //C:\xampp\php\php.exe C:\htdocs\yss\yii cmd/create-menu
    //php \mnt\data0\wwwroot\yss cmd/create-menu
    //d:\xampp\php\php.exe d:\xampp\htdocs\yss\yii cmd/create-menu
    public function actionCreateMenu()
    {    
        $gh_id = Yii::$app->wx->getGhid();
        if ($gh_id == MGh::GH_HOYA)
        {
            $menu = new \backend\modules\wx\models\WxMenu([
                new \backend\modules\wx\models\ButtonComplex('走进爱迪', [
/*
                    new \backend\modules\wx\models\ButtonView('关于爱迪', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/adabout:{$gh_id}")),
                    new \backend\modules\wx\models\ButtonView('校区查询', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/schoolbranch:{$gh_id}")),
                    new \backend\modules\wx\models\ButtonView('教师风采', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/teachershow:{$gh_id}")),
                    new \backend\modules\wx\models\ButtonView('爱迪宝贝秀', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/studentshow:{$gh_id}")),
                    new \backend\modules\wx\models\ButtonView('联系我们', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/adcontact:{$gh_id}")),
*/
                    //new \backend\modules\wx\models\ButtonView('关于爱迪', 'http://backend.hoyatech.net/index.php?r=wx/yss/adabout'),
                    new \backend\modules\wx\models\ButtonClick('关于爱迪', 'FuncMyAdabout'),

                    //new \backend\modules\wx\models\ButtonView('校区查询', 'http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranch'),
                    //new \backend\modules\wx\models\ButtonClick('校区查询', 'FuncMySchoolbranch'),
                    new \backend\modules\wx\models\ButtonLocationSelect('校区查询', 'FuncMySchoolbranch'),

                    //new \backend\modules\wx\models\ButtonView('教师风采', 'http://backend.hoyatech.net/index.php?r=wx/yss/teachershow'),
                    new \backend\modules\wx\models\ButtonClick('教师风采', 'FuncMyTeachershow'),                       

                    //new \backend\modules\wx\models\ButtonView('爱迪宝贝秀', 'http://backend.hoyatech.net/index.php?r=wx/yss/studentshow'),
                    new \backend\modules\wx\models\ButtonClick('爱迪宝贝秀', 'FuncMyStudentshow'),

                    new \backend\modules\wx\models\ButtonView('联系我们', 'http://backend.hoyatech.net/index.php?r=wx/yss/adcontact'),

                ]),
                new \backend\modules\wx\models\ButtonComplex('预约优惠', [
//                    new \backend\modules\wx\models\ButtonView('我要预约', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/reserve:{$gh_id}")),
//                    new \backend\modules\wx\models\ButtonView('课程介绍', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/course:{$gh_id}")),
//                    new \backend\modules\wx\models\ButtonView('近期活动', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/activities:{$gh_id}")),
                    //new \backend\modules\wx\models\ButtonView('教师风采x', 'http://backend.hoyatech.net/index.php?r=wx/yss/teacherx'),
                    //new \backend\modules\wx\models\ButtonView('教师风采y', 'http://backend.hoyatech.net/index.php?r=wx/yss/teachery'),
                    new \backend\modules\wx\models\ButtonClick('我要预约', 'FuncMyReserve'),
                    //new \backend\modules\wx\models\ButtonView('课程介绍', 'http://backend.hoyatech.net/index.php?r=wx/yss/course'),
                    new \backend\modules\wx\models\ButtonClick('课程介绍', 'FuncMyCourses'),

//                    new \backend\modules\wx\models\ButtonView('近期活动', 'http://backend.hoyatech.net/index.php?r=wx/yss/activities'),
//                    new \backend\modules\wx\models\ButtonView('近期活动', 'http://58.221.61.118/mobile.php?act=module&id=20&from_user=MDNiZU0xc2gzZUROZzhKUEZ5MzZxbU9uMEY1RzBBR2RDTFlvRjI4aWxVS2dvVTcvTUNLOXBNdzFkVHlyQmJHRUwzei8ralg5YXVJWA%3D%3D&name=newvote&do=index&weid=1&wxref=mp.weixin.qq.com#wechat_redirect'),
                    new \backend\modules\wx\models\ButtonClick('近期活动', 'FuncMyActivity'),

                ]),
                new \backend\modules\wx\models\ButtonComplex('宝贝查询', [
//                    new \backend\modules\wx\models\ButtonView('签到记录', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/signon:{$gh_id}")),
//                    new \backend\modules\wx\models\ButtonView('宝贝相册', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/babyshow:{$gh_id}")),
//                    new \backend\modules\wx\models\ButtonView('宝贝课表', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/mycourse:{$gh_id}")),
//                    new \backend\modules\wx\models\ButtonView('推荐有礼', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "yss/recommend:{$gh_id}")),

                    new \backend\modules\wx\models\ButtonClick('签到记录', 'FuncMySigon'),
                    new \backend\modules\wx\models\ButtonClick('宝贝相册', 'FuncMyBaby'),
                    new \backend\modules\wx\models\ButtonClick('宝贝课表', 'FuncMyCourse'),
                    new \backend\modules\wx\models\ButtonClick('推荐有礼', 'FuncMyRecommend'),
                    new \backend\modules\wx\models\ButtonClick('绑定管理', 'FuncMyBind'),

                ]),
            ]);
        }
        else
        {
            die("invalid gh_id=$gh_id");
        }
        $menu_json = Wechat::json_encode($menu);
        U::W([$menu, $menu_json]);
        $arr = Yii::$app->wx->WxMenuCreate($menu);
        U::W($arr);
        return;
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/menu-get
    public function actionMenuGet()
    {    
        $arr = Yii::$app->wx->WxMenuGet();    
        U::W($arr);
        return;        
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/menu-delete
    public function actionMenuDelete()
    {    
        $arr = Yii::$app->wx->WxMenuDelete();    
        U::W($arr);
        return;        
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/import-mobilenum
    // /usr/bin/php /mnt/wwwroot/wx/yii cmd/import-mobilenum
    public function actionImportMobilenum()
    {
    
        /*    
        $pattern = "/(?:0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){5}\d/";
        //$ret = preg_match($pattern, '123456789');
        $ret = preg_match($pattern, 'a234567b6789');
        if ($ret)
            echo 'match';
        else
            echo 'no match';
        */
    
        $num_cat = MMobnum::getNumCat(MItem::ITEM_CAT_MOBILE_IPHONE4S);
        $file = Yii::$app->getRuntimePath().DIRECTORY_SEPARATOR.'mobile_num.txt';
        $fh = fopen($file, "r");
        $i = 0;
        $sm_valid_cids = array();
        while (!feof($fh)) 
        {
            $line = fgets($fh);
            if (empty($line))
                continue;

            $mobnum = trim($line);
            $ychf = 0;
            $zdxf = 0;
            $is_good = 1;

            $tableName = MMobnum::tableName();
            $n = Yii::$app->db->createCommand("REPLACE INTO $tableName (num, num_cat, ychf, zdxf, is_good) VALUES (:num, :num_cat, :ychf, :zdxf, :is_good)", [':num' => $mobnum,':num_cat' => $num_cat, ':ychf'=>$ychf, ':zdxf'=>$zdxf, ':is_good'=>$is_good])->execute();

            $i++;
            //if ($i > 2) break;
        }
        fclose($fh);    
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/set-liantong-flag
    // /usr/bin/php /mnt/wwwroot/wx/yii cmd/set-liantong-flag
    public function actionSetLiantongFlag()
    {        
        $gh_id = Yii::$app->wx->getGhid();
        $models = MStaff::find()->where("gh_id = :gh_id AND openid != '' ", [':gh_id'=>$gh_id])->asArray()->all();        
        foreach($models as $model)
        {
            $gh_id = $model['gh_id'];
            $openid = $model['openid'];
            $user = MUser::findOne(['gh_id'=>$gh_id, 'openid'=>$openid]);
            if ($user !== null && $user->is_liantongstaff == 0)
            {
                $user->is_liantongstaff = 1;
                $user->save(false);
            }
        }
    }

    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/sm-balance
    public function actionSmBalance()
    {        
	 echo "guodu:".ESmsGuodu::B(true);
    }

	
}

/*        
        $articles[] = ['title'=>'title-a', 'description'=>'description-a:Analyze UA. This tool was developed for user agent string analysis. Our analysis gives you information on client SW type (browser, webcrawler, anonymizer etc.)', 'url'=>'http://hoyatech.net/wx/web/index.php', 'picurl'=>'http://hoyatech.net/wx/web/images/earth.jpg'];
        $articles[] = ['title'=>'title-b', 'description'=>'description-b', 'url'=>'http://baidu.com'];        
        $news = ['articles'=>$articles];
        $msg = ['touser'=>'oySODt2YXO_JMcFWpFO5wyuEYX-0', 'msgtype'=>'news', 'news'=>$news];

        $menu = new \backend\modules\wx\models\WxMenu([
            new \backend\modules\wx\models\ButtonComplex('热卖商品', [
                new \backend\modules\wx\models\ButtonClick('在线充值', 'FuncChargeOnline'),
                new \backend\modules\wx\models\ButtonView('自选靓号', 'http://hoyatech.net/wx/web/index.php'),
                new \backend\modules\wx\models\ButtonView('精品商店', 'http://hoyatech.net/wx/web/index.php'),
            ]),

            new \backend\modules\wx\models\ButtonComplex('活动有礼', [
                new \backend\modules\wx\models\ButtonClick('签到有礼', 'FuncSignon'),
                new \backend\modules\wx\models\ButtonView('幸运大转盘', 'http://sina.com'),
                new \backend\modules\wx\models\ButtonView('刮刮乐', 'http://baidu.com'),
            ]),

            new \backend\modules\wx\models\ButtonComplex('我的服务', [
                new \backend\modules\wx\models\ButtonClick('话费查询', 'FuncQueryFee'),
                new \backend\modules\wx\models\ButtonView('我的积分', 'http://sina.com'),
                new \backend\modules\wx\models\ButtonClick('客服小沃', 'FuncCustomService'),
            ]),
        ]);
//        new \backend\modules\wx\models\ButtonView('网上营业厅', Yii::$app->wx->WxGetOauth2Url('snsapi_base', urlencode(json_encode(['wap/mall','gh_id'=>Yii::$app->wx->getGhid()])))),

        new \backend\modules\wx\models\ButtonClick('沃竞猜，猜胜负', 'FuncCustomService'),

        //$menu = new \backend\modules\wx\models\WxMenu([
        //    new \backend\modules\wx\models\ButtonComplex('沃4G专柜', [
        //        new \backend\modules\wx\models\ButtonView('自由套餐', 'http://m.10010.com/mobilegoodsdetail/981405149472.html'),
        //        new \backend\modules\wx\models\ButtonView('4G套餐', 'http://m.10010.com/mobilegoodsdetail/981403121719.html'),
        //        new \backend\modules\wx\models\ButtonView('4G手机', 'http://m.10010.com/MobileList'),
        //    ]),
        //    new \backend\modules\wx\models\ButtonView('网上营业厅', Yii::$app->wx->WxGetOauth2Url('snsapi_base', 'wap/mall:'.Yii::$app->wx->getGhid())),
        //    new \backend\modules\wx\models\ButtonComplex('我的服务', [
        //        new \backend\modules\wx\models\ButtonView('自助查询', Url::to(['site/about', 'id'=>1],true)),
        //        new \backend\modules\wx\models\ButtonClick('天天抽话费', 'FuncQueryFee'),
        //        new \backend\modules\wx\models\ButtonClick('签到送积分', 'FuncSignon'),
        //        new \backend\modules\wx\models\ButtonClick('客服小沃', 'FuncCustomService'),
        //        new \backend\modules\wx\models\ButtonView('我要维权', Url::to(['site/index'],true)),
        //    ]),
        //]);

            $menu = new \backend\modules\wx\models\WxMenu([
                new \backend\modules\wx\models\ButtonComplex('产品', [
                    //new \backend\modules\wx\models\ButtonView('精品靓号', 'http://m.10010.com/mobilegoodsdetail/981405149472.html'),
                    new \backend\modules\wx\models\ButtonView('自由组合', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "wap/diy:{$gh_id}")),
                    //new \backend\modules\wx\models\ButtonView('demo0', 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx520c15f417810387&redirect_uri=http%3A%2F%2Fchong.qq.com%2Fphp%2Findex.php%3Fd%3D%26c%3DwxAdapter%26m%3DmobileDeal%26showwxpaytitle%3D1%26vb2ctag%3D4_2030_5_1194_60&response_type=code&scope=snsapi_base&state=123#wechat_redirect'),                
                    new \backend\modules\wx\models\ButtonView('demo', 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf0e81c3bee622d60&redirect_uri=http%3A%2F%2Fnba.bluewebgame.com%2Foauth_response.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'),
                    new \backend\modules\wx\models\ButtonView('沃商城', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "wap/mall:{$gh_id}")),
                ]),
                //new \backend\modules\wx\models\ButtonView('促销商品', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "wap/prom:{$gh_id}")),
                new \backend\modules\wx\models\ButtonView('促销商品', "http://backend.hoyatech.net/index.php?r=wap/prom&gh_id={$gh_id}"),
                new \backend\modules\wx\models\ButtonComplex('我的服务', [
                    //new \backend\modules\wx\models\ButtonClick('个性化账单', 'FuncQueryAccount'),
                    new \backend\modules\wx\models\ButtonClick('本地生活', 'FuncQueryFee'),
                    //new \backend\modules\wx\models\ButtonClick('关注', 'FuncSignon'),
                    //new \backend\modules\wx\models\ButtonClick('吐槽', 'FuncCustomService'),
                    //new \backend\modules\wx\models\ButtonView('我要维权', Url::to(['site/index'],true)),
                    new \backend\modules\wx\models\ButtonView('jsnative', 'http://backend.hoyatech.net//jsnative.php'),
                    new \backend\modules\wx\models\ButtonView('jsphp', 'http://backend.hoyatech.net//jsphp.php'),
                    //new \backend\modules\wx\models\ButtonView('jsjs', 'http://backend.hoyatech.net/test/jsjs.php'),
                    new \backend\modules\wx\models\ButtonView('靓号运程', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "wap/luck:{$gh_id}")),
                    //new \backend\modules\wx\models\ButtonView('游戏2048', 'http://backend.hoyatech.net/test/2048/index.php'),
                    new \backend\modules\wx\models\ButtonView('游戏2048', Yii::$app->wx->WxGetOauth2Url('snsapi_base', "wap/g2048:{$gh_id}")),
                ]),
            ]);


    //C:\xampp\php\php.exe C:\htdocs\wx\yii cmd/tmp
    public function actionTmp()    
    {
        set_time_limit(0);    
//        $filename =  Yii::$app->getRuntimePath()."/a.txt";
        $filename =  Yii::$app->getRuntimePath()."/b.txt";        
        $filename1 =  Yii::$app->getRuntimePath()."/b.txt";        
        $handle = @fopen($filename, "r");
        if (!$handle)
            die("fopen $filename failed");

        $i = 0;
        $rows = array();
        while (!feof($handle)) 
        {
            $data = fgets($handle);
            $data = trim($data);            
            if (empty($data))
                continue;
                if (strlen($data) == 0)
                continue;

            $arr = explode(' ', $data);

            $mob = $arr[0];
            $name = $arr[1];                
            $title = isset($arr[2]) ? $arr[2] : '';                            

            $s = new \backend\modules\wx\models\MStaff;


            $s->gh_id = 'gh_03a74ac96138';
            $s->name = $name;
            $s->mobile = $mob;

            $title = trim($title);
            if (empty($title))
            {
                $s->office_id = 0;            
                U::W("NO PARTMENT, $name");                
            }
            else
            {
                $model = \backend\modules\wx\models\MOffice::findOne(['title'=>$title]);
                if ($model === null)
                {
                    U::W("NOT FOUND THIS PARTMENT $title, $name");
                    $s->office_id = 0;                            
                }
                else
                    $s->office_id = $model->office_id;
            }
            $s->save(false);
            
//            $i++;
//            if ($i>4) break;
            

        }
        fclose($handle);

    }

//C:\xampp\php\php.exe C:\htdocs\yss\yii cmd/thumbnail-all
//php yii cmd/thumbnail-all
public function actionThumbnailAll() 
{
	set_time_limit(0);		
	$files = FileHelper::findFiles(Yii::getAlias('@storage')."/".MPhoto::PHOTO_PATH);
	foreach($files as $file)		
	{
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if (!in_array($ext, ['jpg','jpeg','png','gif']))
			continue;
		MPhoto::thumbnailAll($file);
	}
}
        
*/

