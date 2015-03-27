<?php
namespace console\controllers;

use yii;
use yii\base\InvalidParamException;
use yii\console\Controller;
use yii\helpers\Json;
use common\models\User;
use backend\modules\wx\models\MGh;
use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\U;
use backend\modules\wx\models\WxException;
/**
 * Test controller
 */
class TaskController extends Controller {

    public function actionIndex() {
        $touser = 'oFeeLjiJSd7OEkVgmKFBMVGCup7E';
        $templateId = 'YtGIMW1qlYjAIDBX2OPpTA8wzpsj_y7vMR-oVeeQwHw';
        $url = 'http://www.idealangel.cn';
        $data = array(
            'first' => array('value' => '赵一乔的家长您好，赵一乔已经签到', 'color' => '#0A0A0A'),
            'keyword1' => array('value' => '张三', 'color' => '#CCCCCC'),
            'keyword2' => array('value' => '0001', 'color' => '#CCCCCC'),
            'keyword3' => array('value' => '2015年3月25日', 'color' => '#CCCCCC'),
            'keyword4' => array('value' => '上课签到', 'color' => '#173177'),
            'remark' => array('value' => '感谢您对爱迪天才的支持', 'color' => '#173177')
        );
         Yii::$app->wx->setGhId(MGh::GH_IDEALANGEL);
         //$sign = Yii::$app->wx->getSignPackage();
         //var_dump($_SERVER);
         //$sign = Yii::$app->wx->getSignPackage();
         //var_dump($_SERVER);
         //var_dump($sign);
        $token = Yii::$app->wx->GetAccessToken();
        Yii::$app->wx->sendTemplateMessage($data, $touser, $templateId, $url, $token);
        
    }

}
