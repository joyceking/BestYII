<?php

namespace backend\controllers;

use common\models\MStudentSubcourse;
use Yii;
use common\models\MSchool;

use common\models\MSchoolBranch;
use common\models\MStudent;
use common\models\search\MStudentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentController implements the CRUD actions for MStudent model.
 */
class StudentController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new MStudentSearch();
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
        $model = new MStudent();
		$model->school_id = MSchool::getSchoolIdFromSession();
        
        $model->load(Yii::$app->request->post());

        if ($model->save()) {
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
			//return $this->redirect(['index', 'school_branch_id'=>$model->schoolBranch->school_branch_id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
				'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		//$school_branch_id = $model->school_branch_id;
		$model->delete();
		//return $this->redirect(['index', 'school_branch_id'=>$school_branch_id]);
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = MStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
