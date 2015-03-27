<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\fileapi\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\MKeyword */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mkeyword-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => 256, 'readonly'=>true, 'value'=>$model->comment])->label('参数名') ?>


<?php if ($rich): ?>

    <?= $form->field($model, 'value')->label('值')->widget(
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

<?php else: ?>
	<?= $form->field($model, 'value')->textarea(['rows' => 6])->label('值') ?>

<?php endif; ?>

	<!--
    <//?//= $form->field($model, 'value')->widget(
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
    )->label('值') ?>
	-->
    
	
	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
