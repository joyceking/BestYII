<?php

namespace backend\controllers;

use Yii;
use common\models\MSchool;
use common\models\search\MSchoolSearch;
use common\models\MPhoto;
use common\models\MPhotoOwner;
use backend\modules\wx\models\U;
use common\models\MTeacher;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;

use yii\web\UploadedFile;

use vova07\fileapi\actions\UploadAction as FileAPIUpload;

class MyphotosController extends Controller
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

    public function actionCreate()
    {
        $model = new MPhoto();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->school_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($owner_cat, $owner_id, $id)
    {
		$model = MPhotoOwner::findOne(['owner_cat'=>$owner_cat, 'owner_id'=>$owner_id, 'photo_id'=>$id]);
		$photo = $model->photo;
        if ($model->load(Yii::$app->request->post()) && $photo->load(Yii::$app->request->post())) {
			if ($model->save() && $photo->save())
				return $this->redirect(['index', 'owner_cat'=>$owner_cat, 'owner_id'=>$owner_id]);
        } else {
            return $this->render('update', [
                'photo' => $photo,
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($owner_cat, $owner_id, $id)
    {
		$model = MPhotoOwner::findOne(['owner_cat'=>$owner_cat, 'owner_id'=>$owner_id, 'photo_id'=>$id]);
		$model->delete();
        return $this->redirect(['index', 'owner_cat'=>$owner_cat, 'owner_id'=>$owner_id]);
    }

    protected function findModel($id)
    {
        if (($model = MPhoto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex($owner_cat, $owner_id)
	{
		$params = [':owner_cat' => $owner_cat, ':owner_id' => $owner_id];
		
		$sql = <<<EOD
		SELECT COUNT(*) from yss_photo_owner t1 
		INNER JOIN yss_photo t2 on t1.photo_id = t2.photo_id
		WHERE t1.owner_cat = :owner_cat AND t1.owner_id = :owner_id
EOD;
		$count = Yii::$app->db->createCommand($sql, $params)->queryScalar();		
		
		$sql = <<<EOD
		SELECT t1.photo_id, t1.owner_cat, t1.owner_id, t1.sort_order, t1.photo_owner_id, t1.create_time, t2.* from yss_photo_owner t1 
		INNER JOIN yss_photo t2 on t1.photo_id = t2.photo_id
		WHERE t1.owner_cat = :owner_cat AND t1.owner_id = :owner_id
EOD;

		$dataProvider = new SqlDataProvider([
			'sql' => $sql,
			'key' => 'photo_id',
			'params' => $params,
			'totalCount' => $count,			 
			 'sort' => [
			 	'enableMultiSort' => true,
				'attributes' => [
					'size',
					'sort_order',
					't1.create_time',
					'tags',
				],
				'defaultOrder' => [
					'sort_order' => SORT_DESC,
					't1.create_time' => SORT_DESC,
				]
			 ],
			 
			 'pagination' => [
				 'pageSize' => 20,
			 ],
		]);

		$model = new MPhoto();

		if($model->load(Yii::$app->request->post()))
		{
			if($file = UploadedFile::getInstance($model, 'pic_url'))
			{
				if(!$file->error)
				{
					$targetFileId = date("YmdHis").'-'.uniqid();
					$ext = pathinfo($file->name, PATHINFO_EXTENSION);
					$targetFileName = "{$targetFileId}.{$ext}";
					$targetFile = Yii::getAlias('@storage') . DIRECTORY_SEPARATOR . MPhoto::PHOTO_PATH . DIRECTORY_SEPARATOR . $targetFileName;
					if ($file->saveAs($targetFile))
					{
						$model->pic_url = $targetFileName;
						$model->size = $file->size; 					
						if ($model->save()) {
							$photoOwner = new MPhotoOwner();
							$photoOwner->owner_cat = $owner_cat;
							$photoOwner->owner_id = $owner_id;
							if ($model->savePhotoOwner($photoOwner)) {
								return $this->refresh();
							}
							else
								U::W('errrrrr');
						}
					}
					else
					{
						$model->addError('pic_url', 'save as error!');					
					}
					
				} else {
					$model->addError('pic_url', $file->error);
				}
			
			}			
		}
		
		return $this->render('index', [
			//'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'model' => $model,
		]);
	}
	
}
