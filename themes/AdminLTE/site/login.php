<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:;"><b>Ebizu</b>BS</a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to Ebizu Backend Service</p>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
        <a href="<?= Yii::$app->urlManager->createUrl('site/forgot') ?>">I forgot my password</a><br>
        <a href="<?= Yii::$app->urlManager->createUrl('site/register') ?>" class="text-center">Register a new user</a>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
