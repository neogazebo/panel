<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorkingTIme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="working-time-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'wrk_type')->textInput() ?>

    <?= $form->field($model, 'wrk_by')->textInput() ?>

    <?= $form->field($model, 'wrk_param_id')->textInput() ?>

    <?= $form->field($model, 'wrk_start')->textInput() ?>

    <?= $form->field($model, 'wrk_end')->textInput() ?>

    <?= $form->field($model, 'wrk_time')->textInput() ?>

    <?= $form->field($model, 'wrk_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wrk_created')->textInput() ?>

    <?= $form->field($model, 'wrk_updated')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
