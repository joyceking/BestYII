<?php

namespace backend\controllers;

use Yii;
use common\models\MGroup;
use common\models\search\MGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\UserException;
use common\models\MClass;
use common\models\search\MClassSearch;
use common\models\MSubcourse;
use common\models\MCourse;
use common\models\MCourseUnit;
use backend\modules\wx\models\U;

/**
 * GroupController implements the CRUD actions for MGroup model.
 */
class ClassController extends Controller {

    public function behaviors() {
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
    public function actionIndex() {
        $searchModel = new MClassSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MGroup model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $model = new MClass();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (($course = MSubcourse::findOne($model->course_unit_id)) === null)
                throw new UserException(Yii::t('backend', 'Invalid course.'));
            if ($model->save())
                return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'course_unit_id' => $model->course_unit_id]);
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
    public function actionDelete($id) {
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
    protected function findModel($id) {
        if (($model = MCLass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionStart($group_id) {
        $model = $this->findModel($group_id);
        $groupStudents = $model->groupStudents;
        if (empty($groupStudents))
            throw new NotFoundHttpException(Yii::t('backend', 'Can not start this group because there is not any students in this group!'));

        if ($model->load(Yii::$app->request->post())) {
            $model->status = MClass::GROUP_STATUS_SCHEDULE_DONE;
            if ($model->save()) {
                //U::W([$model, $model->startTime]);
                $model->startSchedule($model->startTime, $model->teacher_id);
                return $this->redirect(['index']);
            }
        }
        return $this->render('groupstart', [
                    'model' => $model,
        ]);
    }

    public function actionEnd($group_id) {
        $model = $this->findModel($group_id);
        $model->status = MClass::GROUP_STATUS_END;
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

