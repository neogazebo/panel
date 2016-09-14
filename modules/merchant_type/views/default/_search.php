<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyTypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-type-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'com_type_id') ?>

    <?= $form->field($model, 'com_type_name') ?>

    <?= $form->field($model, 'com_type_multiple_point') ?>

    <?= $form->field($model, 'com_type_max_point') ?>

    <?= $form->field($model, 'com_type_created_by') ?>

    <?php // echo $form->field($model, 'com_type_created_date') ?>

    <?php // echo $form->field($model, 'com_type_updated_date') ?>

    <?php // echo $form->field($model, 'com_type_deleted_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
