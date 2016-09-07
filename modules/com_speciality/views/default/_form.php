<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanySpeciality */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-speciality-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'com_spt_merchant_speciality_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'com_spt_multiple_point')->textInput() ?>

    <?= $form->field($model, 'com_spt_created_by')->textInput() ?>

    <?= $form->field($model, 'com_spt_created_date')->textInput() ?>

    <?= $form->field($model, 'com_spt_updated_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
