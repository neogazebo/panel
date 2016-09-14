<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyType */
/* @var $form yii\widgets\ActiveForm */
?>
    <?php $form = ActiveForm::begin([
        'id' => 'promo-speciality',
        'action' => $model->isNewRecord ? '/type/default/create' : '/type/default/update',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    ]); ?>
    <div class="modal-body">
    <?= $form->field($model, 'com_type_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'com_type_multiple_point')->textInput() ?>

    <?= $form->field($model, 'com_type_max_point')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="modal-footer">
        <?= Html::button('Back',['class' => 'btn btn-default pull-left','data-dismiss' => 'modal']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>

<?php ActiveForm::end(); ?>
