<?php

namespace backend\controllers;

use Yii;
use common\models\MCourse;
use common\models\MCourseUnit;
use common\models\search\MCourseUnitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CourseunitController extends Controller
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

	public function init()
	{
		//\common\models\U::W([$_GET,$_POST]);	
	}

	public function actionIndex($course_id)
	{
		$course = MCourse::findOne($course_id);		  
		$searchModel = new MCourseUnitSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'course' => $course,			
		]);
	}

	public function actionBatchdelete()
	{
		$ids = Yii::$app->request->post('ids');
		if (!empty($ids))
			$n = MCourseUnit::deleteAll(['course_unit_id' => $ids]);
		return \yii\helpers\Json::encode(['status' => 'success']);		
	}
	
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	public function actionCreate($course_id)
	{
		$course = MCourse::findOne($course_id);
		if ($course->isAvailable())
			throw new \yii\base\UserException(Yii::t('backend', 'Please open the start button first!'));			
	
		$model = new MCourseUnit();
		if ($model->load(Yii::$app->request->post()) && $course->saveCourseUnit($model)) {
			return $this->redirect(['index', 'course_id'=>$course_id]);
		} else {
			$model->sort_order = 1;
			$model->minutes = 45;		
			return $this->render('create', [
				'model' => $model,
				'course'=> $course,
			]);
		}
		
	}

	public function actionTogglestatus($course_id)
	{
		$course = MCourse::findOne($course_id);
		$course->course_unit_is_over = $course->course_unit_is_over ? 0 : 1;
		$course->save();
		return $this->redirect(['index', 'course_id'=>$course_id]);		
	}

	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index', 'course_id'=>$model->course->course_id]);
		} else {
			return $this->render('update', [
				'model' => $model,
				'course'=> $model->course,
			]);
		}
	}

	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$course_id = $model->course_id;
		$model->delete();
		return $this->redirect(['index', 'course_id'=>$course_id]);
	}

	protected function findModel($id)
	{
		if (($model = MCourseUnit::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
}
