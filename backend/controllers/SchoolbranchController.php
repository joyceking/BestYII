<?php

namespace backend\controllers;

use Yii;
use common\models\MSchool;
use common\models\MSchoolBranch;
use common\models\search\MSchoolBranchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SchoolBranchController implements the CRUD actions for MSchoolBranch model.
 */
class SchoolbranchController extends Controller
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

    public function actionIndex($school_id)
    {
		$school = MSchool::findOne($school_id);    
        $searchModel = new MSchoolBranchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'school'=> $school,            
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($school_id)
    {
		$school = MSchool::findOne($school_id);
        $model = new MSchoolBranch();
		if ($model->load(Yii::$app->request->post()) && $school->saveSchoolBranch($model)) {
            return $this->redirect(['index', 'school_id'=>$school_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'school'=> $school,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'school_id'=>$model->school->school_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
		$school_id = $model->school_id;
        $model->delete();
        return $this->redirect(['index', 'school_id'=>$school_id]);
    }

    protected function findModel($id)
    {
        if (($model = MSchoolBranch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
