<?php

use app\components\helpers\GlobalHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanySpeciality */
/* @var $form yii\widgets\ActiveForm */
$country = new GlobalHelper();
?>
    <?php $form = ActiveForm::begin([
        'id' => 'promo-speciality',
        'action' => $model->isNewRecord ? '/speciality/default/create' : '/promo/default/update',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    ]); ?>
    <div class="modal-body">

    <?= $form->field($model, 'com_spt_type')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'com_spt_cty_id')->dropDownList($country->TempCountry(),['prompt' => 'Select Country...']) ?>

    <?= $form->field($model, 'com_spt_multiple_point')->textInput() ?>

    <?= $form->field($model, 'com_spt_max_point')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="modal-footer">
        <?= Html::button('Back',['class' => 'btn btn-default pull-left','data-dismiss' => 'modal']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
<?php ActiveForm::end(); ?>

