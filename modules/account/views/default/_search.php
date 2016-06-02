<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'acc_id') ?>

    <?= $form->field($model, 'acc_facebook_id') ?>

    <?= $form->field($model, 'acc_facebook_email') ?>

    <?= $form->field($model, 'acc_facebook_graph') ?>

    <?= $form->field($model, 'acc_google_id') ?>

    <?php // echo $form->field($model, 'acc_google_email') ?>

    <?php // echo $form->field($model, 'acc_google_token') ?>

    <?php // echo $form->field($model, 'acc_screen_name') ?>

    <?php // echo $form->field($model, 'acc_cty_id') ?>

    <?php // echo $form->field($model, 'acc_photo') ?>

    <?php // echo $form->field($model, 'acc_created_datetime') ?>

    <?php // echo $form->field($model, 'acc_updated_datetime') ?>

    <?php // echo $form->field($model, 'acc_status') ?>

    <?php // echo $form->field($model, 'acc_tmz_id') ?>

    <?php // echo $form->field($model, 'acc_birthdate') ?>

    <?php // echo $form->field($model, 'acc_address') ?>

    <?php // echo $form->field($model, 'acc_gender') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
