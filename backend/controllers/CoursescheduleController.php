<?php

namespace backend\controllers;

use Yii;
use common\models\MGroup;

use common\models\MCourseSchedule;
use common\models\search\MCourseScheduleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\modules\wx\models\U;

class CoursescheduleController extends Controller
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

    public function actionIndex($group_id)
    {
		$group = MGroup::findOne($group_id);            
        $searchModel = new MCourseScheduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$courseSchedules = $group->courseSchedules;
		if (empty($courseSchedules)) {
			//$courseSchedules = $group->createCourseSchedules();
			throw new NotFoundHttpException(Yii::t('backend', 'Please start group first'));
		}
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'group' => $group,
            'courseSchedules' => $courseSchedules,
        ]);
    }

    /**
     * Displays a single MCourseSchedule model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MCourseSchedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MCourseSchedule();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->course_schedule_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MCourseSchedule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index', 'group_id' => $model->group->group_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MCourseSchedule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MCourseSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MCourseSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MCourseSchedule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
