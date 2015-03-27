<?php

namespace backend\controllers;

use Yii;
use common\models\MTeacher;
use common\models\search\MTeacherSearch;
use common\models\MSchool;
use common\models\MCourseSchedule;
use common\models\MCourseScheduleSignon;

use backend\modules\wx\models\U;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use vova07\fileapi\actions\UploadAction;

class TeacherController extends Controller
{
    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => UploadAction::className(),
				'path' => '@backend/runtime/tmp',
				'uploadOnlyImage' => false,
            ]
        ];
    }

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

    public function actionIndex()
    {
        $searchModel = new MTeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new MTeacher();
		$model->school_id = MSchool::getSchoolIdFromSession();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        return $this->redirect(['index']);			
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = MTeacher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
/*
	public function actionSignlist($teacher_id)
	{
        $model = $this->findModel($teacher_id);
		$courseSchedules = MCourseSchedule::find()->where('teacher_id=:teacher_id AND DATE(start_time)=:start_time', [':teacher_id'=>$teacher_id, ':start_time'=>date("Y-m-d")])->orderBy('course_schedule_id')->all();
		if (empty($courseSchedules)) {
            throw new NotFoundHttpException('You have no course today!');
		}
		
		return $this->render('signlist', [
			'model' => $model,
			'courseSchedules' => $courseSchedules,
		]);		
	}

	public function actionSignon($signon_id)
	{
		$model = MCourseScheduleSignon::findOne($signon_id);
		if ($model === null) {
            throw new NotFoundHttpException('Invalid signon_id:{$signon_id}!');
		}		
		$model->is_signon = MCourseScheduleSignon::SIGNON_STATUS_YES;
		if (!$model->save()) {
            throw new NotFoundHttpException('save signon to db failed!');
		}
		
        return $this->redirect(['signlist', 'teacher_id'=>$model->courseSchedule->teacher_id]);		
	}
*/	
}
