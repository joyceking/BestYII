<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\MSchool;
use common\models\MSchoolBranch;
use common\models\MCourse;

$mcourse = new MCourse();
?>

<div class="mstudent-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'school_branch_id')->dropDownList(\yii\helpers\ArrayHelper::map(
        \common\models\MSchoolBranch::find()->where(['school_id'=>\common\models\MSchool::getSchoolIdFromSession()])->all(),
        'school_branch_id',
        'title'
    ))->label('校区') ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => 16]) ?>

	<?= $form->field($model, 'sex')->dropDownList(MSchool::getSexOptionName()) ?>	

    <?= $form->field($model, 'birthday')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', ['phpDatetimeFormat'=>'yyyy-MM-dd']) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 16]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

