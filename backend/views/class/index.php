<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\MClass;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Group');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mgroup-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'group_id',
            'title',
            [
                'label' => '课程名称',
                'value' => function ($model, $key, $index, $column) {
                    return (empty($model->subCourse) ? '' : $model->subCourse->getSubCourseFullInfo());
                },
                'filter' => true,
            ],
            [
                'label' => '班内人数',
                'format' => 'html',
                'value' => function ($model, $key, $index, $column) {
                    return count($model->groupStudents) . ' ' . Html::a('<span>详情</span>', ['classstudent/index', 'group_id' => $model->group_id]);
                },
                    ],
                    [
                        'label' => '状态',
                        'format' => 'html',
                        'value' => function ($model, $key, $index, $column) {
                            if ($model->status == MClass::GROUP_STATUS_NONE)
                                return MClass::getGroupStatusOptionName($model->status) . ' ' . Html::a('<span>开班</span>', ['class/start', 'group_id' => $model->group_id]);
                            else if ($model->status == Mclass::GROUP_STATUS_SCHEDULE_DONE)
                                return MClass::getGroupStatusOptionName($model->status) . ' ' . Html::a('<span>结班</span>', ['class/end', 'group_id' => $model->group_id]);
                            else
                                return MClass::getGroupStatusOptionName($model->status);
                        },
                            ],
                            [
                                'label' => '课时计划',
                                'format' => 'html',
                                'value' => function ($model, $key, $index, $column) {
                                    if ($model->status == MClass::GROUP_STATUS_NONE)
                                        return '请先开班后再查看计划详情';
                                    else
                                        return Html::a('<span>详情</span>', ['courseschedule/index', 'group_id' => $model->group_id]);
                                },
                                    ],
                                    [
                                        'label' => '班相册',
                                        'format' => 'html',
                                        'value' => function ($model, $key, $index, $column) {
                                            return $model->getPhotosCount() . ' ' . Html::a('<span>详情</span>', ['myphotos/index', 'owner_cat' => $model->ownerCat, 'owner_id' => $model->getPrimaryKey()]);
                                        },
                                            ],
//			'status',
                                            'create_time',
                                            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
                                        ],
                                    ]);
                                    ?>

</div>
