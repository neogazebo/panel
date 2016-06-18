<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SearchWorkingTime */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="working-time-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'wrk_id') ?>

    <?= $form->field($model, 'wrk_type') ?>

    <?= $form->field($model, 'wrk_by') ?>

    <?= $form->field($model, 'wrk_param_id') ?>

    <?= $form->field($model, 'wrk_start') ?>

    <?php // echo $form->field($model, 'wrk_end') ?>

    <?php // echo $form->field($model, 'wrk_time') ?>

    <?php // echo $form->field($model, 'wrk_description') ?>

    <?php // echo $form->field($model, 'wrk_created') ?>

    <?php // echo $form->field($model, 'wrk_updated') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
