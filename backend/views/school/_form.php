<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use vova07\fileapi\Widget;

?>

<div class="mschool-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'slogan')->textInput(['maxlength' => 256]) ?>

    <?php //echo $form->field($model, 'logo_url')->textInput(['maxlength' => 256]) ?>

            <?= $form->field($model, 'logo_url')->widget(Widget::className(),
                [
                    'settings' => [
                        'url' => ['fileapi-upload']
                    ],
/*
                    'crop' => true,
                    'cropResizeWidth' => 100,
                    'cropResizeHeight' => 100,
*/
                ]
            ) ?>


    <?= $form->field($model, 'des')->widget(
        \yii\imperavi\Widget::className(),
        [
            'plugins' => ['fullscreen','fontcolor'],
            'options'=>[
                'minHeight'=>200,
                'maxHeight'=>400,
                'buttonSource'=>true,
                'convertDivs'=>false,
                'removeEmptyTags'=>false,
                'imageUpload'=>Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
            ]
        ]
    ) ?>

    <?= $form->field($model, 'history')->widget(
        \yii\imperavi\Widget::className(),
        [
            'plugins' => ['fullscreen','fontcolor'],
            'options'=>[
                'minHeight'=>200,
                'maxHeight'=>400,
                'buttonSource'=>true,
                'convertDivs'=>false,
                'removeEmptyTags'=>false,
                'imageUpload'=>Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
            ]
        ]
    ) ?>

    <?= $form->field($model, 'addr')->textInput(['maxlength' => 256]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => 256]) ?>

    <?php echo $form->field($model, 'gh_id')->textInput(['maxlength' => 32])->label('微信原始公众号ID') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
/*
    <?= $form->field($model, 'des')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    <?= $form->field($model, 'des')->widget(
        \yii\imperavi\Widget::className(),
        [
            'plugins' => ['fullscreen','fontcolor'],
            'options'=>[
                'minHeight'=>400,
                'maxHeight'=>400,
                'buttonSource'=>true,
                'convertDivs'=>false,
                'removeEmptyTags'=>false,
                'imageUpload'=>Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
            ]
        ]
    ) ?>

*/