<?php

namespace backend\controllers;

use Yii;
use common\models\MGroup;
use common\models\search\MGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\UserException;

use common\models\MCourse;
use common\models\MCourseUnit;

use backend\modules\wx\models\U;

/**
 * GroupController implements the CRUD actions for MGroup model.
 */
class GroupController extends Controller
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
     * Lists all MGroup models.
     * @return mixed
     */
    public function actionIndex($course_unit_id)
    {
		$courseUnit = MCourseUnit::findOne($course_unit_id);      
        $searchModel = new MGroupSearch();
        $searchModel->course_unit_id = $course_unit_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new MGroup(); 
        if ($model->load(Yii::$app->request->post())) {
            //if (($course = MCourse::findOne($model->course_id)) === null)
            //  throw new UserException(Yii::t('backend', 'Invalid course.'));          
            //if (!($course->isAvailable()))
            //  throw new UserException(Yii::t('backend', 'Please finish making course unit first.'));            
			$model->course_unit_id = $courseUnit->course_unit_id;
			if ($model->save()) {                
	            $model = new MGroup();			                
	            return $this->redirect(['index', 'course_unit_id'=>$courseUnit->course_unit_id]);
            }
        }        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'courseUnit' => $courseUnit,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single MGroup model.
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
     * Creates a new MGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($course_unit_id)
    {
        $model = new MGroup();
		$courseUnit = MCourseUnit::findOne($course_unit_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//if (($course = MCourse::findOne($model->course_id)) === null)
			//	throw new UserException(Yii::t('backend', 'Invalid course.'));			
			//if (!($course->isAvailable()))
			//	throw new UserException(Yii::t('backend', 'Please finish making course unit first.'));		
			$model->course_unit_id = $courseUnit->course_unit_id;
			if ($model->save()) {
	            return $this->redirect(['index', 'course_unit_id'=>$courseUnit->course_unit_id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'courseUnit' => $courseUnit                
            ]);
        }
    }

    /**
     * Updates an existing MGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        return $this->redirect(['index', 'course_unit_id'=>$model->course_unit_id]);					
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MGroup model.
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
     * Finds the MGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionStart($group_id)
    {
        $model = $this->findModel($group_id);	
		$groupStudents = $model->groupStudents;
		if (empty($groupStudents))
            throw new NotFoundHttpException(Yii::t('backend', 'Can not start this group because there is not any students in this group!'));

        if ($model->load(Yii::$app->request->post())) {
			$model->status = MGroup::GROUP_STATUS_SCHEDULE_DONE;			
			if ($model->save())
			{
				$model->startSchedule($model->startTime, $model->teacher_id);
	            return $this->redirect(['index', 'course_unit_id'=>$model->courseUnit->course_unit_id]);
			}
        }
        return $this->render('groupstart', [
            'model' => $model,
        ]);
    }

	public function actionEnd($group_id)
	{
        $model = $this->findModel($group_id);		
		$model->status = MGroup::GROUP_STATUS_END;
		$model->save(false);				
        return $this->redirect(['index']);		
	}
	
}

/*
	public function actionStart($group_id)
	{
        $model = $this->findModel($group_id);	
		$groupStudents = $model->groupStudents;
		if (empty($groupStudents))
            throw new NotFoundHttpException(Yii::t('backend', 'Can not start this group because there is not any students in this group!'));

		$model->status = MGroup::GROUP_STATUS_SCHEDULE_DONE;
		$model->save(false);				
        return $this->redirect(['index']);		
	}
*/

