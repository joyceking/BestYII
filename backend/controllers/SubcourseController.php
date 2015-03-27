<?php

namespace backend\controllers;

use Yii;
use common\models\MSubcourse;
use common\models\search\MSubcourseSearch;

use common\models\MCourse;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * SubcourseController implements the CRUD actions for MSubcourse model.
 */
class SubcourseController extends Controller
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
     * Lists all MSubcourse models.
     * @return mixed
     */
    public function actionIndex($course_id)
    {
		$course = MCourse::findOne($course_id);    
        $searchModel = new MSubcourseSearch();
        $searchModel->course_id = $course_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new MSubcourse();         
        if ($model->load(Yii::$app->request->post())) 
        {
            $model->course_id = $course_id;
            if ($model->save()) {
                $model = new MSubcourse();         
            }
        }        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'course'=> $course, 
            'model'=> $model,                        
        ]);        
    }

    /**
     * Displays a single MSubcourse model.
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
     * Creates a new MSubcourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($course_id)
    {
        $model = new MSubcourse();
        $model->course_id = $course_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirect(['index', 'course_id'=>$model->course_id]);            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MSubcourse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'course_id'=>$model->course_id]);            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MSubcourse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);    
        $model->delete();

        return $this->redirect(['index', 'course_id'=>$model->course_id]);            
    }

    /**
     * Finds the MSubcourse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MSubcourse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MSubcourse::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
