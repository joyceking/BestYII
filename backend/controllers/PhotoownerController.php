<?php

namespace backend\controllers;

use Yii;
use common\models\MPhoto;
use common\models\MPhotoOwner;
use common\models\search\MPhotoOwnerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PhotoownerController extends Controller
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

    public function actionIndex($photo_id)
    {
		$photo = MPhoto::findOne($photo_id);    
        $searchModel = new MPhotoOwnerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$model = new MPhotoOwner();
		$model->photo_id = $photo->photo_id;
		if ($model->load(Yii::$app->request->post())) {
			if ($photo->savePhotoOwner($model)) {
				$model = new MPhotoOwner();	
				$model->photo_id = $photo->photo_id;				
			}
		}

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'photo'=> $photo,            
            'model'=> $model,                        
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'photo_id'=>$model->photo->photo_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'photo'=> $model->photo,                
            ]);
        }
    }

    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
		$photo_id = $model->photo_id;
        $model->delete();
        return $this->redirect(['index', 'photo_id'=>$photo_id]);
    }

    protected function findModel($id)
    {
        if (($model = MPhotoOwner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

/*
    public function actionCreate($photo_id)
    {
		$photo = MPhoto::findOne($photo_id);
        $model = new MPhotoOwner();
		if ($model->load(Yii::$app->request->post()) && $photo->savePhotoOwner($model)) {
            return $this->redirect(['index', 'photo_id'=>$photo_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'photo'=> $photo,
            ]);
        }
    }

*/

