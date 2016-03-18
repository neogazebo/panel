<div class="login-box">
    <div class="login-logo">
        <a href="javascript:;"><b>Ebizu</b>BS</a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to Ebizu Backend Service</p>
        <form action="<?= Yii::$app->homeUrl ?>" method="post">
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div><!-- /.col -->
            </div>
        </form>

        <a href="<?= Yii::$app->urlManager->createUrl('site/forgot') ?>">I forgot my password</a><br>
        <a href="<?= Yii::$app->urlManager->createUrl('site/register') ?>" class="text-center">Register a new user</a>

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
