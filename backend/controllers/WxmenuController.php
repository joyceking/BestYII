<?php

namespace backend\controllers;

use Yii;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

use common\models\MWxMenu;
use common\models\MSchool;

use backend\modules\wx\models\U;
use backend\modules\wx\models\WxException;
use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\MGh;

class WxmenuController extends Controller
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

    public function init()
    {
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());		
		$gh = $school->gh;
		if (empty($gh->gh_id)) {
            throw new NotFoundHttpException('This school has invalid gh id');
		}    
    }
	
    public function actionIndex()
    {
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());		
		$gh = $school->gh;    
		$modelsSort = [];  
		$models = MWxMenu::getSubModels($gh->gh_id);
		if (empty($models)) {
			MWxMenu::importFromWechat($gh->gh_id);
			$models = MWxMenu::getSubModels($gh->gh_id);			
		}		
		foreach ($models as $model) {
			$modelsSort[] = $model;
			if ($model->is_sub_button) {
				$subModels = MWxMenu::getSubModels($gh->gh_id, $model->wx_menu_id);
				$modelsSort = array_merge($modelsSort, $subModels);
			}
		}
		$dataProvider = new ArrayDataProvider([
			'allModels' => $modelsSort,
			'key'=>'wx_menu_id',
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		return $this->render('index', [
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
        $model = new MWxMenu();
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $model->gh_id = $school->gh->gh_id;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->is_sub_button) {
                $model->type = '';
                $model->parent_id = 0;
                $model->url = '';
            }
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->sort_order = MWxMenu::getNextSortOrder($model->gh_id);
            return $this->render('create', [
                'model' => $model,
                'gh'=>$school->gh,
            ]);
        }
    }

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
                'gh'=>$school->gh,
            ]);
        }
    }

    /**
     * Deletes an existing MWxMenu model.
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
     * Finds the MWxMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MWxMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MWxMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionExport()
	{
		$school = MSchool::findOne(MSchool::getSchoolIdFromSession());		
		$gh = $school->gh;
		$done = MWxMenu::exportToWechat($gh->gh_id);
        Yii::$app->session->setFlash('success', $done ? '成功保存到微信服务器！' : '保存失败');
        return $this->redirect(['index']);
	}

    public function actionImport()
    {
        $school = MSchool::findOne(MSchool::getSchoolIdFromSession());
        $gh = $school->gh;
        MWxMenu::importFromWechat($gh->gh_id);
        return $this->redirect(['index']);
    }

}

/*
*/

