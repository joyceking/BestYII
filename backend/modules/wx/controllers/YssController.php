<?php

namespace backend\modules\wx\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\View;
use yii\data\ActiveDataProvider;
use backend\modules\wx\models\U;
use backend\modules\wx\models\WxException;
use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\MGh;
use common\models\MSchool;
use common\models\MSchoolBranch;
use common\models\MTeacher;
use common\models\MPhoto;
use common\models\MPhotoOwner;
use common\models\MOpenidOwner;
use common\models\MStudent;
use common\models\MReserve;
use common\models\MRecommend;
use common\models\MCourse;
use common\models\MKeyword;
use common\models\MCourseSchedule;
use common\models\MCourseScheduleSignon;

class YssController extends Controller {

    //http://127.0.0.1/wx/web/index.php?r=yss/adabout
    //http://127.0.0.1/yss/backend/web/index.php?r=wx/yss/adabout
    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/adabout:gh_78539d18fdcc
    /*
      public function actionAdabout()
      {
      $this->layout = 'yss';
      #$gh_id = U::getSessionParam('gh_id');
      #$openid = U::getSessionParam('openid');
      #Yii::$app->wx->setGhId($gh_id);
      #return $this->render('adabout', ['gh_id'=>$gh_id, 'openid'=>$openid]);
      $school = MSchool::findOne(MSchool::getSchoolIdFromSession());


      $photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_SCHOOL, MSchool::getSchoolIdFromSession(), 2);
      U::W($photos);
      //U::W($photos[0]->pic_url);

      $photo_owner = MPhotoOwner::findOne(['owner_cat'=>MPhotoOwner::PHOTO_OWNER_SCHOOL]);
      U::W($photo_owner->photo->pic_url);
      return $this->render('adabout', ['photo'=>$photo_owner->photo]);
      }
     */
    public $jsapiPackage;
    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        Yii::$app->wx->setGhId(MGh::GH_IDEALANGEL);
        $this->jsapiPackage = Yii::$app->wx->getSignPackage();
    }
    
    public function actionAdabout($gh_id, $openid) {
        $this->layout = false;
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        return $this->render('adabout', ['gh_id' => $gh_id, 'openid' => $openid, 'school' => $school]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/yss/schoolbranch
    //http://backend.hoyatech.net/index.php?r=wx/yss/schoolbranch
    public function actionSchoolbranch($gh_id, $openid) {
        $this->layout = false;
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $schoolbranchs = $school->schoolBranches;
        U::W($schoolbranchs);
        return $this->render('schoolbranch', ['gh_id' => $gh_id, 'openid' => $openid, 'schoolbranchs' => $schoolbranchs]);
    }

    public function actionSchoolregion($gh_id, $openid) {
        $this->layout = false;
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $schoolbranchs = $school->schoolBranches;
        U::W($schoolbranchs);
        return $this->render('schoolregion', ['gh_id' => $gh_id, 'openid' => $openid, 'schoolbranchs' => $schoolbranchs]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/schoolbranchshowdetail:gh_78539d18fdcc
    public function actionSchoolbranchshowdetail($gh_id, $openid, $school_branch_id) {
        $this->layout = false;
        //$school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        //$schoolbranchs = $school->schoolBranches;
        $schoolbranch = MSchoolBranch::findOne($school_branch_id);

        return $this->render('schoolbranchshowdetail', ['gh_id' => $gh_id, 'openid' => $openid, 'schoolbranch' => $schoolbranch]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/schoolbranchnav:gh_78539d18fdcc
    public function actionSchoolbranchnav() {
        $this->layout = false;
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $schoolbranchs = $school->schoolBranches;
        U::W($schoolbranchs);
        return $this->render('schoolbranchnav', ['schoolbranchs' => $schoolbranchs, 'lon_begin' => 0, 'lat_begin' => 0]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/teachershow:gh_78539d18fdcc
    public function actionTeachershow($gh_id, $openid) {
        $this->layout = false;
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $teachers = $school->teachers;
        //U::W('=============');
        // U::W($teachers);
        // foreach($schoolbranchs as $schoolbranch)
        // {
        //   $photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_SCHOOLBRANCH, $schoolbranch->schoolbranch_id, 2);
        //}

        return $this->render('teachershow', ['gh_id' => $gh_id, 'openid' => $openid, 'teachers' => $teachers]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/teachershowdetail:gh_78539d18fdcc
    public function actionTeachershowdetail($gh_id, $openid, $teacher_id) {
        $this->layout = false;
        //$school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        //$teachers = $school->teachers;
        //U::W('=============');
        // U::W($teachers);
        // foreach($schoolbranchs as $schoolbranch)
        // {
        //   $photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_SCHOOLBRANCH, $schoolbranch->schoolbranch_id, 2);
        //}
        //return $this->render('teachershowdetail', ['teachers'=>$teachers]);

        $teacher = MTeacher::findOne($teacher_id);
        return $this->render('teachershowdetail', ['gh_id' => $gh_id, 'openid' => $openid, 'teacher' => $teacher]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/studentshow:gh_78539d18fdcc
    public function actionStudentshow($gh_id, $openid) {
        $this->layout = false;

        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());

        $students = $school->students;
        U::W('=============');
        U::W($students);
        // foreach($schoolbranchs as $schoolbranch)
        // {
        //   $photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_SCHOOLBRANCH, $schoolbranch->schoolbranch_id, 2);
        //}

        return $this->render('studentshow', ['gh_id' => $gh_id, 'openid' => $openid, 'students' => $students]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/adcontact:gh_78539d18fdcc
    public function actionAdcontact() {
        $this->layout = false;

        $keyword = MKeyword::find()->select('*')->where("keyword=:keyword", [':keyword' => 'contact_us'])->asArray()->one();
        return $this->render('adcontact', ['keyword' => $keyword]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/course:gh_78539d18fdcc
    public function actionCourse($gh_id, $openid) {
        $this->layout = false;

        if (empty($_GET['teacher_id'])) {
            $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
            $courses = $school->courses;
        } else {
            $teacher = MTeacher::findOne($_GET['teacher_id']);
            $courses = $teacher->courses;
        }

        return $this->render('course', ['courses' => $courses, 'gh_id' => $gh_id, 'openid' => $openid]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/courseshowdetail:gh_78539d18fdcc
    public function actionCourseshowdetail($gh_id, $openid, $course_id) {
        $this->layout = false;
//        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
//        $courses = $school->courses;
        $course = MCourse::findOne($course_id);

        return $this->render('courseshowdetail', ['gh_id' => $gh_id, 'openid' => $openid, 'course' => $course]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/activities:gh_78539d18fdcc
    public function actionActivities() {
        $this->layout = false;
        return $this->render('activities');
    }

    //http://127.0.0.1/wx/web/index.php?r=wapx/nearestmap&gh_id=gh_03a74ac96138&openid=oKgUduJJFo9ocN8qO9k2N5xrKoGE&office_id=1&lon=114.361676377&lat=30.5824773524
    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/nearestmap:gh_78539d18fdcc
    public function actionNearestmap($gh_id, $openid, $school_branch_id, $lon, $lat) {
        $this->layout = false;
        $schoolbranch = MSchoolBranch::findOne($school_branch_id);

        return $this->render('nearestmap', ['schoolbranch' => $schoolbranch, 'lon_begin' => $lon, 'lat_begin' => $lat, 'lon_end' => $schoolbranch->lon, 'lat_end' => $schoolbranch->lat]);
    }

    public function actionSignlist($teacher_id) {
        $this->layout = false;
        $model = MTeacher::findOne($teacher_id);
        $courseSchedules = MCourseSchedule::find()->where('teacher_id=:teacher_id AND DATE(start_time)=:start_time', [':teacher_id' => $teacher_id, ':start_time' => date("Y-m-d")])->orderBy('course_schedule_id')->all();
        if (empty($courseSchedules)) {
            throw new NotFoundHttpException('You have no course today!');
        }

        return $this->render('signlist', [
                    'model' => $model,
                    'courseSchedules' => $courseSchedules,
        ]);
    }

    public function actionSignon($signon_id) {
        $this->layout = false;
        $model = MCourseScheduleSignon::findOne($signon_id);
        if ($model === null) {
            throw new NotFoundHttpException('Invalid signon_id:{$signon_id}!');
        }
        $model->is_signon = MCourseScheduleSignon::SIGNON_STATUS_YES;
        if (!$model->save()) {
            throw new NotFoundHttpException('save signon to db failed!');
        }

        return $this->redirect(['signlist', 'teacher_id' => $model->courseSchedule->teacher_id]);
    }

    public function actionSignoff($signon_id) {
        $model = MCourseScheduleSignon::findOne($signon_id);
        if ($model === null) {
            throw new NotFoundHttpException('Invalid signon_id:{$signon_id}!');
        }
        $model->is_signon = MCourseScheduleSignon::SIGNON_STATUS_NO;
        if (!$model->save()) {
            throw new NotFoundHttpException('save signon to db failed!');
        }

        return $this->redirect(['signlist', 'teacher_id' => $model->courseSchedule->teacher_id]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/yss/teacher
    public function actionTeacher() {
        $this->layout = 'yss';
        #$gh_id = U::getSessionParam('gh_id');
        #$openid = U::getSessionParam('openid');
        #Yii::$app->wx->setGhId($gh_id); 
        #return $this->render('adabout', ['gh_id'=>$gh_id, 'openid'=>$openid]);
        return $this->render('teacher');
    }

    //http://127.0.0.1/wx/web/index.php?r=yss/teacherx
    public function actionTeacherx() {
        $this->layout = false;
        #$gh_id = U::getSessionParam('gh_id');
        #$openid = U::getSessionParam('openid');
        #Yii::$app->wx->setGhId($gh_id); 
        #return $this->render('adabout', ['gh_id'=>$gh_id, 'openid'=>$openid]);
        return $this->render('teacherx');
    }

    //http://127.0.0.1/wx/web/index.php?r=yss/teachery
    public function actionTeachery() {
        $this->layout = 'yss';
        #$gh_id = U::getSessionParam('gh_id');
        #$openid = U::getSessionParam('openid');
        #Yii::$app->wx->setGhId($gh_id); 
        #return $this->render('adabout', ['gh_id'=>$gh_id, 'openid'=>$openid]);
        return $this->render('teachery');
    }

    //http://127.0.0.1/wx/web/index.php?r=yss/teacherz
    public function actionTeacherz() {
        $this->layout = false;
        #$gh_id = U::getSessionParam('gh_id');
        #$openid = U::getSessionParam('openid');
        #Yii::$app->wx->setGhId($gh_id); 
        #return $this->render('adabout', ['gh_id'=>$gh_id, 'openid'=>$openid]);
        return $this->render('teacherz');
    }

    //http://127.0.0.1/wx/web/index.php?r=yss/myreserve
    //http://127.0.0.1/yss/backend/web/index.php?r=wx/yss/myreserve&gh_id=gh_78539d18fdcc&openid=o6biBt5yaB7d3i0YTSkgFSAHmpdo
    public function actionMyreserve($gh_id, $openid) {
        $this->layout = false;
        $model = new MReserve();
        $model->gh_id = $gh_id;
        $model->openid = $openid;

        if ($openid == 'nobody') {
            return $this->render('subscribepage');
        }

        if (!empty($_GET['course_id']))
            $model->course_id = $_GET['course_id'];

        if (!empty($_GET['school_branch_id']))
            $model->school_branch_id = $_GET['school_branch_id'];

        $query = MReserve::find()->where(['gh_id' => $gh_id, 'openid' => $openid]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'reserve successfully'));
                return $this->refresh();
            }
        }


        $keyword = MKeyword::find()->select('*')->where("keyword=:keyword", [':keyword' => 'reserve_hint'])->asArray()->one();

        return $this->render('myreserve', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'keyword' => $keyword,
        ]);
    }

    public function actionMyreservedelete($id) {
        if (($model = MReserve::findOne($id)) !== null) {
            $model->delete();
        }
        return $this->redirect(['myreserve', 'gh_id' => $model->gh_id, 'openid' => $model->openid]);
    }

    public function actionMyrecommend($gh_id, $openid, $student_ids) {
        $this->layout = false;
        $model = new MRecommend();
        $model->gh_id = $gh_id;
        $model->openid = $openid;

        $query = MRecommend::find()->where(['gh_id' => $gh_id, 'openid' => $openid]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'recommend successfully'));
                return $this->refresh();
            }
        }


        $keyword = MKeyword::find()->select('*')->where("keyword=:keyword", [':keyword' => 'recommend_info'])->asArray()->one();

        return $this->render('myrecommend', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'keyword' => $keyword
        ]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/wap/oauth2cb&state=yss/mybaby:gh_78539d18fdcc
    //http://127.0.0.1/yss/backend/web/index.php?r=wx/yss/mybaby&gh_id=gh_78539d18fdcc&openid=o6biBt5yaB7d3i0YTSkgFSAHmpdo&student_ids=1
    public function actionMybaby($gh_id, $openid, $student_ids) {
        $this->layout = false;
        $student_ids = explode(':', $student_ids);
        $student_id = $student_ids[0];
//		return 'aa'.$student_id;
        //$school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        //$students = $school->students;
        //U::W('=============');
        //U::W($students);
        // foreach($schoolbranchs as $schoolbranch)
        // {
        //   $photos = MPhotoOwner::getPhotosByOwner(MPhotoOwner::PHOTO_OWNER_SCHOOLBRANCH, $schoolbranch->schoolbranch_id, 2);
        //}

        return $this->render('mybaby', ['gh_id' => $gh_id, 'openid' => $openid, 'student_id' => $student_id,'signPackage'=>$this->jsapiPackage]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/yss/mycourse&gh_id=gh_78539d18fdcc&openid=o6biBt5yaB7d3i0YTSkgFSAHmpdo&student_ids=1
    //http://backend.hoyatech.net/index.php?r=wx/yss/mycourse&gh_id=gh_78539d18fdcc&openid=o6biBt39f64QmSLOkIQaJMmBMBFA&student_ids=4:5
    public function actionMycourse($gh_id, $openid, $student_ids) {
        $this->layout = false;
        Yii::$app->wx->setGhId($gh_id);
        $student_ids = explode(':', $student_ids);
        foreach ($student_ids as $student_id) {
            $student = MStudent::findOne($student_id);
            if ($student !== null) {
//				$courseSchedules = $student->getCourseSchedulesX();
                $courseSchedules = $student->getCourseSchedulesY();
//				U::W($courseSchedules);			
                $students[] = $student;
            }
        }
        return $this->render('mycourse', ['students' => $students]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/yss/mysigon&gh_id=gh_78539d18fdcc&openid=o6biBt5yaB7d3i0YTSkgFSAHmpdo&student_ids=1
    //http://backend.hoyatech.net/index.php?r=wx/yss/mysigon&gh_id=gh_78539d18fdcc&openid=o6biBt39f64QmSLOkIQaJMmBMBFA&student_ids=4:5
    public function actionMysigon($gh_id, $openid, $student_ids) {
        $this->layout = false;
        Yii::$app->wx->setGhId($gh_id);
        $students = [];
        $student_ids = explode(':', $student_ids);
        foreach ($student_ids as $student_id) {
            $student = MStudent::findOne($student_id);
            if ($student !== null) {
//				$signons = $student->getCourseScheduleSignonsX();
                $signons = $student->getCourseScheduleSignonsY();
//				U::W($signons);
                $students[] = $student;
            }
        }
        return $this->render('mysigon', ['students' => $students]);
    }

    //http://127.0.0.1/yss/backend/web/index.php?r=wx/yss/mybind&gh_id=gh_78539d18fdcc&openid=o6biBt5yaB7d3i0YTSkgFSAHmpdo
    public function actionMybind($gh_id, $openid) {
        $this->layout = 'yss';
        $model = new MOpenidOwner();
        $model->gh_id = $gh_id;
        $model->openid = $openid;

        $query = MOpenidOwner::find()->where(['gh_id' => $gh_id, 'openid' => $openid]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->mobile) && empty($model->owner_id)) {
                $model->addError('mobile', Yii::t('backend', 'Mobile and ID can not be empty at the same time.'));
                $model->addError('owner_id', Yii::t('backend', 'Mobile and ID can not be empty at the same time.'));
            } elseif (empty($model->owner_id)) {
                if ($model->owner_cat == MOpenidOwner::OPENID_OWNER_STUDENT) {
                    $ar = MStudent::findOne(['mobile' => $model->mobile]);
                    if ($ar === null || $ar->name != $model->owner_name) {
                        $model->addError('owner_name', Yii::t('app', 'Invalid name or this student does not exists.'));
                    }
                    $model->owner_id = $ar->student_id;
                } else {
                    $ar = MTeacher::findOne(['mobile' => $model->mobile]);
                    if ($ar === null || $ar->name != $model->owner_name) {
                        $model->addError('owner_name', Yii::t('app', 'Invalid name or this teacher does not exists.'));
                    }
                    $model->owner_id = $ar->teacher_id;
                }
                $ar = MOpenidOwner::findOne(['gh_id' => $gh_id, 'openid' => $openid, 'owner_cat' => $model->owner_cat, 'owner_id' => $model->owner_id]);
                if ($ar !== null) {
                    $model->addError('owner_id', Yii::t('app', 'Can not bind this ID many times..'));
                }
            } else {
                $ar = MOpenidOwner::findOne(['gh_id' => $gh_id, 'openid' => $openid, 'owner_cat' => $model->owner_cat, 'owner_id' => $model->owner_id]);
                if ($ar !== null) {
                    $model->addError('owner_id', Yii::t('app', 'Can not bind this ID many times'));
                } elseif ($model->owner_cat == MOpenidOwner::OPENID_OWNER_STUDENT) {
                    $ar = MStudent::findOne(['student_id' => $model->owner_id]);
                    if ($ar === null || $ar->name != $model->owner_name) {
                        $model->addError('owner_name', Yii::t('app', 'Invalid name or this student id does not exists.'));
                    }
                } elseif ($model->owner_cat == MOpenidOwner::OPENID_OWNER_TEACHER) {
                    $ar = MTeacher::findOne(['teacher_id' => $model->owner_id]);
                    if ($ar === null || $ar->name != $model->owner_name) {
                        $model->addError('owner_name', Yii::t('app', 'Invalid name or this teacher id does not exists.'));
                    }
                }
            }

            if ((!$model->hasErrors()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Bind successfully'));
                return $this->refresh();
            }
        }

        return $this->render('mybind', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

    public function actionMybinddelete($id) {
        if (($model = MOpenidOwner::findOne($id)) !== null) {
            $model->delete();
        }
        return $this->redirect(['mybind', 'gh_id' => $model->gh_id, 'openid' => $model->openid]);
    }

}
