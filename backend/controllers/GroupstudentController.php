<?php

namespace backend\controllers;

use Yii;
use common\models\MGroup;
use common\models\MGroupStudent;
use common\models\search\MGroupStudentSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupstudentController implements the CRUD actions for MGroupStudent model.
 */
class GroupstudentController extends Controller
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
     * Lists all MGroupStudent models.
     * @return mixed
     */
    public function actionIndex($group_id)
    {
		$group = MGroup::findOne($group_id);        
        $searchModel = new MGroupStudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$model = new MGroupStudent();
		if ($model->load(Yii::$app->request->post())) 
		{
			//Join this group
			if ($group->saveGroupStudent($model))
			{
				// if the group is already started, insert some signon records
				if ($group->status == MGroup::GROUP_STATUS_SCHEDULE_DONE)
					$group->startSchedule();
				$model = new MGroupStudent();			
			}
		}

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'group'=> $group,                        
            'model'=> $model,            
        ]);
    }

    /**
     * Displays a single MGroupStudent model.
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
     * Creates a new MGroupStudent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MGroupStudent();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->group_student_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MGroupStudent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->group_student_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MGroupStudent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
        $model->delete();
		return $this->redirect(['index', 'group_id' => $model->group_id]);
    }

    /**
     * Finds the MGroupStudent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MGroupStudent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MGroupStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
