<?php

/*
    http://wosotech.com/wx/web/index.php    
    http://wosotech.com/wx/web/index.php?r=msg&gh_id=gh_1ad98f5481f3        //woso
    http://wosotech.com/wx/web/index.php?r=msg&gh_id=gh_78539d18fdcc        //hoya    
    http://wosotech.com/wx/web/index.php?r=msg&gh_id=gh_1ad98f5481f3
    http://127.0.0.1/wx/web/index.php?r=msg&gh_id=gh_1ad98f5481f3            //woso
    http://127.0.0.1/wx/web/index.php?r=msg&gh_id=gh_78539d18fdcc            //hoya
    http://127.0.0.1/wx/web/index.php?r=msg&gh_id=gh_03a74ac96138            //xiangyangunicom

    http://wosotech.com/wx/web/index.php?r=msg&gh_id=gh_78539d18fdcc
	127.0.0.1/yss/backend/web/wx/msg?gh_id=gh_78539d18fdcc
	127.0.0.1/yss/backend/web/index.php?r=wx/msg&gh_id=gh_78539d18fdcc
	http://backend.hoyatech.net/wx/msg?gh_id=gh_78539d18fdcc
*/

namespace backend\modules\wx\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;

use backend\modules\wx\models\U;
use backend\modules\wx\models\WxException;
use backend\modules\wx\models\MGh;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\Wechat;

class MsgController extends Controller
{
    public $enableCsrfValidation = false;

    public $layout = false;	

    public function actionIndex($gh_id)
    {
        //$wxConfig = require(__DIR__ . '/../config/wx.php');
		$wxConfig = require(Yii::getAlias('@common').'/config/wx.php');
        switch ($gh_id) 
        {
            case MGh::GH_IDEALANGEL:
                $wxConfig['class'] = 'backend\modules\wx\models\WechatHoya';
                break;

            default:
				U::W(['Invalid gh_id in MsgController', $_GET]);	
				die('Invalid gh_id in MsgController');
                break;
        }
        $wechat = \Yii::createObject($wxConfig);        
        return $wechat->run($gh_id);    
    }

    public function afterAction($action, $result)
    {
        U::W("{$this->id}/{$this->action->id}:".Yii::getLogger()->getElapsedTime());
        return parent::afterAction($action, $result);
    }

    //http://wosotech.com/wx/web/index.php?r=msg/valid&token=HY09uB1h
    //http://127.0.0.1/yss/backend/web/index.php?r=wx/msg/valid&token=Cy0Etqot00wFz50y5l5fffO7yQ05YZ0X
    //http://127.0.0.1/wx/web/index.php?r=msg/valid&token=HY09uB1h
    //http://127.0.0.1/yss/backend/web/wx/msg/valid?token=Cy0Etqot00wFz50y5l5fffO7yQ05YZ0X        
    //http://backend.hoyatech.net/wx/msg/valid?token=Cy0Etqot00wFz50y5l5fffO7yQ05YZ0X
    //http://backend.hoyatech.net/index.php?r=wx/msg/valid&token=Cy0Etqot00wFz50y5l5fffO7yQ05YZ0X
    public function actionValid($token)
    {
        if (0)
        {
            $_GET['signature'] = '228c2744ce651fb61cceb461c48fa03c608c1299';
            $_GET['echostr'] = '6372428126615300095';
            $_GET['timestamp'] = '1402529705';
            $_GET['nonce'] = '1023195716';
        }
        if (!Wechat::checkSignature($token))
        {
            U::W(['Invalid Signature in actionValid()', $_GET]);
        }
        die($_GET['echostr']);
    }
}

