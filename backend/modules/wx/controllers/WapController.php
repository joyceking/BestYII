<?php

namespace backend\modules\wx\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\modules\wx\models\U;
use backend\modules\wx\models\WxException;
use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\MGh;

use common\models\MSchool;


class WapController extends Controller
{

	public $layout = false; 

/*
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
*/
	
    public function init()
    {
        //U::W(['init....', $_GET,$_POST, $GLOBALS]);
        U::W(['wap init....', $_GET,$_POST]);
    }

    public function beforeAction($action)
    {
        return true;
    }


    public function afterAction($action, $result)
    {
        U::W("{$this->id}/{$this->action->id}:".Yii::getLogger()->getElapsedTime());
        return parent::afterAction($action, $result);
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

	//127.0.0.1/yss/backend/web/wx/wap/index
    public function actionIndex()
    {
        return $this->render('index');
    }

    //http://127.0.0.1/wx/web/index.php?r=wap/oauth2cb&state=wap/luck:gh_1ad98f5481f3
    //http://127.0.0.1/wx/web/index.php?r=wap/oauth2cb&state=wap/luck:gh_1ad98f5481f3:cid=11:oid=12
    public function actionOauth2cb()
    {
        if (Yii::$app->wx->localTest)
        {
            $openid = MGh::GH_XIANGYANGUNICOM_OPENID_HBHE;
            //list($route, $gh_id) = explode(':', $_GET['state']);
            $arr = explode(':', $_GET['state']);
            $route = $arr[0];
            $gh_id = $arr[1];
            unset($arr[0], $arr[1]);
            $r[] = $route;
            foreach($arr as $str)
            {
                list($key, $val) = explode('=', $str);
                $r[$key] = $val;
            }
            Yii::$app->session['gh_id'] = $gh_id;
            Yii::$app->session['openid'] = $openid;            
            $user = MUser::findOne(['gh_id'=>$gh_id, 'openid'=>$openid]);
            //if ($user !== null)
            //    Yii::$app->user->login($user);
            //return $this->redirect([$route, 'gh_id'=>$gh_id, 'openid'=>$openid]);
            //return $this->redirect([$route]);
            return $this->redirect($r);
        }
    
        if (empty($_GET['code']))
        {
            U::W([__METHOD__, $_GET, 'no code']);
            return;
        }        
        $code = $_GET['code'];
        if ($code == 'authdeny')
        {
            return 'Sorry, we can not do anything for you without your authrization!';
        }
        
        $arr = explode(':', $_GET['state']);
        $route = $arr[0];
        $gh_id = $arr[1];
        unset($arr[0], $arr[1]);
        $r[] = $route;
        foreach($arr as $str)
        {
            list($key, $val) = explode('=', $str);
            $r[$key] = $val;
        }

        Yii::$app->wx->setGhId($gh_id);
        $token = Yii::$app->wx->WxGetOauth2AccessToken($code);
        if (!isset($token['access_token']))
        {
            U::W([__METHOD__, $token]);
            return null;
        }
        $oauth2AccessToken = $token['access_token'];
        $openid = $token['openid'];

        if (isset($token['scope']) && $token['scope'] == 'snsapi_userinfo')
        {
            $oauth2UserInfo = Yii::$app->wx->WxGetOauth2UserInfo($oauth2AccessToken, $openid);
            U::W($oauth2UserInfo);
            Yii::$app->session->set('oauth2UserInfo', $oauth2UserInfo);
        }
        Yii::$app->session['gh_id'] = $gh_id;
        Yii::$app->session['openid'] = $openid;
        return $this->redirect($r);
    }

    //http://127.0.0.1/wx/web/index.php?r=wap/oauth2cb&state=wap/product:gh_1ad98f5481f3
    //http://127.0.0.1/wx/web/index.php?r=wap/oauth2cb&state=wap/product:gh_03a74ac96138  

	//http://127.0.0.1/yss/backend/web/wx/wap/product
	//http://127.0.0.1/yss/backend/web/wx/wap/oauth2cb?state=wap/product:gh_03a74ac96138  
    public function actionProduct()
    {
        $this->layout ='wapy';
        //$this->layout =false;
        $gh_id = U::getSessionParam('gh_id');
        $openid = U::getSessionParam('openid'); 
		$school_id = MSchool::getSchoolIdFromSession();
		$model = MSchool::findOne($school_id);
		U::W($model->getAttributes());
		
        return $this->render('product',['gh_id'=>$gh_id, 'openid'=>$openid]);
    }


}
