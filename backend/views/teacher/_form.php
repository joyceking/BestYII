<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\MSchool;
use vova07\fileapi\Widget;

?>

<div class="mteacher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 16]) ?>



	<?= $form->field($model, 'sex')->dropDownList(MSchool::getSexOptionName()) ?>	

    <?= $form->field($model, 'birthday')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', ['phpDatetimeFormat'=>'yyyy-MM-dd']) ?>

	<?= $form->field($model, 'nationality')->dropDownList(MSchool::getNationalityOptionName()) ?>

    <?= $form->field($model, 'identify_id')->textInput(['maxlength' => 32]) ?>



    <?= $form->field($model, 'onboard_time')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', ['phpDatetimeFormat'=>'yyyy-MM-dd']) ?>

	<?= $form->field($model, 'head_url')->widget(Widget::className(),
		[
			'settings' => [
				'url' => ['fileapi-upload']
			],
			'crop' => true,
			'cropResizeWidth' => 100,
			'cropResizeHeight' => 100,
		]
	) ?>

	<?= $form->field($model, 'body_url')->widget(Widget::className(),
		[
			'settings' => [
				'url' => ['fileapi-upload']
			],
			'crop' => false,
			'cropResizeWidth' => 100,
			'cropResizeHeight' => 100,
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
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

/*
	<?= $form->field($model, 'des')->textarea(['rows' => 6])->label('简介') ?>


*/
