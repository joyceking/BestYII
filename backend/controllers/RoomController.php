<?php

namespace backend\controllers;

use Yii;
use common\models\MSchool;
use common\models\MSchoolBranch;
use common\models\MRoom;
use common\models\search\MRoomSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RoomController implements the CRUD actions for MRoom model.
 */
class RoomController extends Controller
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

    public function actionIndex($school_branch_id)
    {
		$schoolBranch = MSchoolBranch::findOne($school_branch_id);        
        $searchModel = new MRoomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'schoolBranch' => $schoolBranch,            
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($school_branch_id)
    {
		$schoolBranch = MSchoolBranch::findOne($school_branch_id);
        $model = new MRoom();
		if ($model->load(Yii::$app->request->post()) && $schoolBranch->saveRoom($model)) {
			return $this->redirect(['index', 'school_branch_id'=>$school_branch_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'schoolBranch'=> $schoolBranch,
            ]);
        }
		
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index', 'school_branch_id'=>$model->schoolBranch->school_branch_id]);
        } else {
            return $this->render('update', [
				'model' => $model
            ]);
        }
    }

    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
		$school_branch_id = $model->school_branch_id;
        $model->delete();
        return $this->redirect(['index', 'school_branch_id'=>$school_branch_id]);
    }

    protected function findModel($id)
    {
        if (($model = MRoom::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
