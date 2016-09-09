<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ComSpecialityPromoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="com-speciality-promo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'spt_promo_id') ?>

    <?= $form->field($model, 'spt_promo_com_spt_id') ?>

    <?= $form->field($model, 'spt_promo_description') ?>

    <?= $form->field($model, 'spt_promo_multiple_point') ?>

    <?= $form->field($model, 'spt_promo_created_by') ?>

    <?php // echo $form->field($model, 'spt_promo_start_date') ?>

    <?php // echo $form->field($model, 'spt_promo_end_date') ?>

    <?php // echo $form->field($model, 'spt_promo_created_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
