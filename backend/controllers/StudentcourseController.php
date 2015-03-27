<?php

namespace backend\controllers;

use Yii;
use common\models\MStudent;
use common\models\MStudentCourse;
use common\models\MCourse;

use common\models\search\MStudentCourseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\UserException;

class StudentcourseController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex($student_id)
    {		
		$student = MStudent::findOne($student_id);  
//$student->getGroups();
//$student->getCourseSchedulesX();
//$student->getCourseScheduleSignonsX();

		


        $searchModel = new MStudentCourseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$model = new MStudentCourse(); 
        if ($model->load(Yii::$app->request->post())) 
		{
			if (($course = MCourse::findOne($model->course_id)) === null)
				throw new UserException(Yii::t('backend', 'Invalid course.'));			
			if (!($course->isAvailable()))
				throw new UserException(Yii::t('backend', 'Please finish making course unit first.'));

			if ($student->saveStudentCourse($model))
			{
//				$student->createCourseScheduleAndSignon($course);
	            $model = new MStudentCourse();			
			}
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'student'=> $student,            
            'model'=> $model,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'student_id'=>$model->student->student_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'student'=> $model->student,                
            ]);
        }
    }

    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$student_id = $model->student_id;
		$model->delete();
		return $this->redirect(['index', 'student_id'=>$student_id]);		
    }

    protected function findModel($id)
    {
        if (($model = MStudentCourse::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

/*
    public function actionCreate($student_id)
    {
		$student = MStudent::findOne($student_id);
        $model = new MStudentCourse();
        if ($model->load(Yii::$app->request->post()) && $student->saveStudentCourse($model)) {
            return $this->redirect(['index', 'student_id'=>$student_id]);
        } else {
			$model->score = 0;
            return $this->render('create', [
                'model' => $model,
                'student'=> $student,
            ]);
        }
    }
*/

