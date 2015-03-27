<?php

namespace backend\controllers;

use Yii;
use common\models\MSubcourse;

use common\models\MSubcourseCourseUnit;
use common\models\search\MSubcourseCourseUnitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubcoursecourseunitController implements the CRUD actions for MSubcourseCourseUnit model.
 */
class SubcoursecourseunitController extends Controller
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

    /**
     * Lists all MSubcourseCourseUnit models.
     * @return mixed
     */
    public function actionIndex($subcourse_id)
    {
        $subCourse = MSubcourse::findOne($subcourse_id);   
        $searchModel = new MSubcourseCourseUnitSearch();
        $searchModel->subcourse_id = $subcourse_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$model = new MSubcourseCourseUnit();         
        if ($model->load(Yii::$app->request->post())) 
		{
			if ($subCourse->saveSubcourseCourseUnit($model)) {
                $model = new MSubcourseCourseUnit();         
			}
        }        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subcourse'=> $subCourse,
            'model'=> $model,            
        ]);        
    }

    /**
     * Displays a single MSubcourseCourseUnit model.
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
     * Creates a new MSubcourseCourseUnit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MSubcourseCourseUnit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->subcourse_course_unit_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MSubcourseCourseUnit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->subcourse_course_unit_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MSubcourseCourseUnit model.
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
     * Finds the MSubcourseCourseUnit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MSubcourseCourseUnit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MSubcourseCourseUnit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
