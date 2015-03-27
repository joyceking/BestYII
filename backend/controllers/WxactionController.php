<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

use common\models\MWxAction;
use common\models\search\MWxActionSearch;
use common\models\MWxMenu;
use common\models\MSchool;

use backend\modules\wx\models\U;
use backend\modules\wx\models\WxException;
use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\MGh;
use backend\behaviors\UploadBehavior;
use vova07\fileapi\actions\UploadAction;

/**
 * WxActionController implements the CRUD actions for MWxAction model.
 */
class WxactionController extends Controller
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

    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => UploadAction::className(),
				'path' => '@backend/runtime/tmp',
				'uploadOnlyImage' => false,
            ]
        ];
    }

    public function init()
    {
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());		
		$gh = $school->gh;
		if (empty($gh->gh_id)) {
            throw new NotFoundHttpException('This school has invalid gh id');
		}    
    }

    /**
     * Lists all MWxAction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MWxActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MWxAction model.
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
     * Creates a new MWxAction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MWxAction();
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $model->gh_id = $school->gh->gh_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MWxAction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $model->gh_id = $school->gh->gh_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MWxAction model.
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
     * Finds the MWxAction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MWxAction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MWxAction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
