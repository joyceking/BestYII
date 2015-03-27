<?php

use yii\helpers\Html;


$this->title = Yii::t('backend', 'Create');
//$this->title = Yii::t('backend', 'Photo Owner');
$this->params['breadcrumbs'][] =  ['label'=>$photo->title, 'url'=>['/photo']];
$this->params['breadcrumbs'][] =  ['label'=>Yii::t('backend', 'Photo Owner'), 'url'=>['index', 'photo_id'=>$photo->photo_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mphoto-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
