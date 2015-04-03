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
use common\models\MSubcourse;
use common\models\MSubcourseCourseUnit;
use common\models\MCourse;
use common\models\MCourseSchedule;
use common\models\MCourseUnit;
use common\models\MCourseScheduleSignon;
use common\models\MClass;

/**
 * Test controller
 */
class TaskController extends Controller {

    public function actionIndex() {
        Yii::$app->wx->setGhId(MGh::GH_IDEALANGEL);
        $templateId = 'BQg98TXJyqz0Y1P5J0obovhl2KCagGHvAYJqqdS9ZT4';
        $readyClasses = MCourseSchedule::find()->where('DATE(start_time) = :start_time', [':start_time' => date("Y-m-d", strtotime('+1 day'))])->all();
        foreach ($readyClasses as $readyClass) {
            $ClassTitle = $readyClass->courseUnitInfo;
            $CourseTitle = $readyClass->courseTitle->title;
            $ClassTime = $readyClass->start_time;
            $ClassSignons = $readyClass->courseScheduleSignons;
            foreach ($ClassSignons as $ClassSignon) {
                $tousers = $ClassSignon->student->ownerId;
                if (is_array($tousers)) {
                    foreach ($tousers as $touser) {
                        $newtouser = $touser['openid'];
                        $url = "http://b.idealangel.cn/index.php?r=wx/yss/mycourse&gh_id=gh_85f1ee57e887&openid=" . $newtouser . "&student_ids=" . $touser['owner_id'];
                        $data = array(
                            'userName' => array('value' => $ClassSignon->student->name . '家长', 'color' => '#E50066'),
                            'courseName' => array('value' => $CourseTitle . "/" . $ClassTitle, 'color' => '#FF6600'),
                            'date' => array('value' => $ClassTime, 'color' => '#00CC00'),
                            'courseState' => array('value' => '上课签到', 'color' => '#FF0000'),
                            'remark' => array('value' => '请注意准时到校参加课程！感谢您对爱迪天才的支持！用心做服务！用爱做教育！总部热线：4000-999-027！', 'color' => '#2E2EB2')
                        );
                        $token = Yii::$app->wx->GetAccessToken();
                        Yii::$app->wx->sendTemplateMessage($data, $newtouser, $templateId, $url, $token);
                    }
                } else {
                    $newtouser = $touser['openid'];
                    $url = "http://b.idealangel.cn/index.php?r=wx/yss/mycourse&gh_id=gh_85f1ee57e887&openid=" . $newtouser . "&student_ids=" . $touser['owner_id'];
                    $data = array(
                            'userName' => array('value' => $ClassSignon->student->name . '家长', 'color' => '#E50066'),
                            'courseName' => array('value' => $CourseTitle . "/" . $ClassTitle, 'color' => '#FF6600'),
                            'date' => array('value' => $ClassTime, 'color' => '#00CC00'),
                            'courseState' => array('value' => '上课签到', 'color' => '#FF0000'),
                            'remark' => array('value' => '请注意准时到校参加课程！感谢您对爱迪天才的支持！用心做服务！用爱做教育！总部热线：4000-999-027！', 'color' => '#2E2EB2')
                        );
                    $token = Yii::$app->wx->GetAccessToken();
                    Yii::$app->wx->sendTemplateMessage($data, $newtouser, $templateId, $url, $token);
                }
                //var_dump($touser);
                //var_dump($ClassSignon->student->name);
            }
        }
        exit;
    }

    public function actionTest() {
        /*
          $subCourse = MSubcourse::findOne(2);
          $subcourseunits = $subCourse->subcourseCourseUnits;
          foreach ($subcourseunits as $subModel)
          {
          var_dump($subModel);
          }
          //var_dump($subcourseunits);
         * $orders = Order::find()->innerJoinWith('books')->all();
         */
        //$courseSchedule = MCourseSchedule::find()->indexBy('course_schedule_id')->all();
        //var_dump($courseSchedule->group_id);
        //foreach ($courseSchedule as $model){
        // var_dump($model->group_id);
        //}

        /*
          $courseGroup = MClass::findOne(10)->courseSchedules;
          foreach($courseGroup as $Group){
          var_dump($Group->group_id);
          }
          //var_dump($courseGroup->courseSchedules->group_id);
         */
        //$course = MCourse::find()->with('subcourses')->where(['school_id' => \common\models\MSchool::getSchoolIdFromSession()])->all();
        /*
          $course = MCourse::find()->joinWith('subcourses')->where(['school_id' => \common\models\MSchool::getSchoolIdFromSession()])->all();
          foreach ($course as $subcourses){
          var_dump($subcourses['subcourses']);
          }
          exit;
          $course = $course->subcourses;
          foreach ($course as $subcourse) {
          var_dump($subcourse->title);
          } */
        $schedule = MCourseSchedule::findBySql('SELECT * FROM yss_course_schedule WHERE group_id = :group_id AND UNIX_TIMESTAMP(start_time) >= :start_time LIMIT 1', [':group_id' => 1, ':start_time' => time()])->asArray()->one(); //'course_unit_id' => $courseUnit->course_unit_id]);
        var_dump($schedule);
    }

}
