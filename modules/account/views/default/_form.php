<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'acc_facebook_id')->textInput() ?>

    <?= $form->field($model, 'acc_facebook_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acc_facebook_graph')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'acc_google_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acc_google_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acc_google_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acc_screen_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acc_cty_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acc_photo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acc_created_datetime')->textInput() ?>

    <?= $form->field($model, 'acc_updated_datetime')->textInput() ?>

    <?= $form->field($model, 'acc_status')->textInput() ?>

    <?= $form->field($model, 'acc_tmz_id')->textInput() ?>

    <?= $form->field($model, 'acc_birthdate')->textInput() ?>

    <?= $form->field($model, 'acc_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acc_gender')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
