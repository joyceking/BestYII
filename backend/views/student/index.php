<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\MSchool;

/*
  $this->title = Yii::t('backend', '学生');
  $this->params['breadcrumbs'][] =  ['label'=>$schoolBranch->school->title, 'url'=>['/school']];
  $this->params['breadcrumbs'][] =  ['label'=>$schoolBranch->title, 'url'=>['/schoolbranch', 'school_id'=>$schoolBranch->school->school_id]];
  $this->params['breadcrumbs'][] = $this->title;
 */

$this->title = Yii::t('backend', 'Student');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mstudent-index">

<?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
<?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'student_id',
        /*
          [
          'attribute' => 'school_branch_id',
          'headerOptions' => array('style'=>'width:80px;'),
          'filter'=> false,
          ],
         */
        'name',
        [
            'attribute' => 'sex',
            'value' => function ($model, $key, $index, $column) {
                return MSchool::getSexOptionName($model->sex);
            },
            'filter' => MSchool::getSexOptionName(),
        ],
        'birthday',
        [

            'label' => '所属校区',
            'attribute' => 'school_branch_id',
            'value' => function ($model, $key, $index, $column) {
                return empty($model->schoolBranch->title) ? '' : $model->schoolBranch->title;
            },
            'filter' => \yii\helpers\ArrayHelper::map(
                    \common\models\MSchoolBranch::find()->where(['school_id' => \common\models\MSchool::getSchoolIdFromSession()])->all(), 'school_branch_id', 'title'
            ),
        ],
        // 'nationality',
        // 'create_time',
        // 'is_delete',
        /*
          [
          'label' => '课程数量',
          'format'=>'html',
          'value'=>function ($model, $key, $index, $column) {
          foreach($model->studentCourses as $studentCourse)
          $arr[] = $studentCourse->course->title;
          $str = empty($arr) ? '' : ' ['.implode(', ', $arr).']';
          return count($model->studentCourses) . $str .' '.Html::a('<span>详情</span>', ['studentcourse/index', 'student_id'=>$model->student_id], [
          'title' => '课程',
          ]);
          },
          ],

         */
        [
            'label' => '所报课程',
            'format' => 'html',
            'value' => function ($model, $key, $index, $column) {
                foreach ($model->studentSubcourses as $studentSubcourse)
                    $arr[] = $studentSubcourse->subcourse->title;
                $str = empty($arr) ? '' : ' [' . implode(', ', $arr) . ']';
                return count($model->studentSubcourses) . $str . ' ' . Html::a('<span>详情</span>', ['studentsubcourse/index', 'student_id' => $model->student_id], [
                            'title' => '课程',
                ]);
            },
                ],
                /*
                  [
                  'label' => '个人相册',
                  'format'=>'html',
                  'value'=>function ($model, $key, $index, $column) {
                  return Html::a('<span>详情</span>', ['studentphoto/index', 'student_id'=>$model->student_id]);
                  },
                  ],
                 */
                [
                    'label' => '学生相册',
                    'format' => 'html',
                    'value' => function ($model, $key, $index, $column) {
                        return $model->getPhotosCount() . ' ' . Html::a('<span>详情</span>', ['myphotos/index', 'owner_cat' => $model->ownerCat, 'owner_id' => $model->getPrimaryKey()]);
                    },
                        ],
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
                    ],
                ]);
                ?>

</div>
