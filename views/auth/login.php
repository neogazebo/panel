<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Sign in';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss("
  .login-page, .register-page {
      background: #222d32 none repeat scroll 0 0;
  }

  .login-logo a{
      color: #367fa9;
  }
");
?>

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Ebizu</b></a>
  </div><!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><?= $this->title ?></p>
    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?> 
      <div class="form-group has-feedback">
          <?= $form->field($model, 'username')->label(false) ?>
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <?= $form->field($model, 'password')->passwordInput()->label(false) ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
          </div>
        </div><!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div><!-- /.col -->
      </div>
    <?php ActiveForm::end(); ?>

    <div class="social-auth-links text-center">

    </div>

  </div>
</div>