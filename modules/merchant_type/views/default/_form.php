<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model app\models\CompanyType */
/* @var $form yii\widgets\ActiveForm */
?>
    <?php $form = ActiveForm::begin([
        'id' => $model->isNewRecord ? 'type_speciality' : 'type_speciality-'.$model->com_type_id,
        'action' => $model->isNewRecord ? '/type/default/create' : '/type/default/update?id='.$model->com_type_id,
        // 'enableAjaxValidation' => true,
    ]); ?>
    <?php
    $point = (floatval($model->com_type_multiple_point) == 0) ? '' : floatval($model->com_type_multiple_point);
    ?>
    <div class="modal-body">
        <?= $form->field($model, 'com_type_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'com_type_multiple_point')->textInput([
            'value' => $point,
            'placeholder' => 'ex : 1.5'
            ]) ?>
        <?= $form->field($model, 'com_type_max_point')->textInput() ?>
    </div>
    <div class="clearfix"></div>

    <div class="modal-footer">
        <?= Html::resetButton('<i class="fa fa-times"></i> Back', ['class' => 'btn btn-default pull-left','onclick' => 'formReset()']) ?>
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> Create' : '<i class="fa fa-check"></i> Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>

<?php ActiveForm::end(); ?>
