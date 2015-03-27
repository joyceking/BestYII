<?php

namespace backend\controllers;

use Yii;
use common\models\MCourseSchedule;

use common\models\MCourseScheduleSignon;
use common\models\search\MCourseScheduleSignonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CourseschedulesignonController extends Controller
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

    public function actionIndex($course_schedule_id)
    {
		$courseSchedule = MCourseSchedule::findOne($course_schedule_id);            
		$courseScheduleSignons = $courseSchedule->courseScheduleSignons;
		if (empty($courseScheduleSignons)) {
			throw new NotFoundHttpException(Yii::t('backend', 'Please start group first'));	// MGroup::startSchedule()		
		}
    
        $searchModel = new MCourseScheduleSignonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'courseSchedule' => $courseSchedule,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$courseSchedule = MCourseSchedule::findOne($model->course_schedule_id);            

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'course_schedule_id' => $model->course_schedule_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'courseSchedule' => $courseSchedule,                
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
        if (($model = MCourseScheduleSignon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
