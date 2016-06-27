<?php

use yii\helpers\Html;
use yii\debug\Toolbar;
use app\themes\AdminLTE\assets\AppAsset;

AppAsset::register($this);
$this->beginPage();
$this->registerCss("
    .table { margin-bottom: 0 }
    .summary { float: left; padding: 12px 0; }
    .pagination { margin: 5px; float: right }
    .mailbox-controls { padding: 0; padding-bottom: 10px; border-bottom: 1px solid #f4f4f4 }
");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo Html::encode($this->title); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta property='og:site_name' content='<?php echo Html::encode($this->title); ?>' />
        <meta property='og:title' content='<?php echo Html::encode($this->title); ?>' />
        <meta property='og:description' content='<?php echo Html::encode($this->title); ?>' />
        <?= Html::csrfMetaTags() ?>
        <?php $this->head(); ?>
    </head>

    <body class="hold-transition skin-blue sidebar-mini">
        <?php $this->beginBody(); ?>
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="<?= Yii::$app->homeUrl ?>" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>v3</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Admin</b>Panel<i>v3</i></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->
                            <!-- <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-success">4</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 4 messages</li>
                                    <li>
                                        <ul class="menu">
                                            <li>
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        Support Team
                                                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                    </h4>
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="<?= Yii::$app->urlManager->createUrl('message/index') ?>">See All Messages</a></li>
                                </ul>
                            </li> -->
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?= $this->theme->baseUrl ?>/dist/img/ebz_logo.png" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?= (isset(Yii::$app->user->identity)) ? Yii::$app->user->identity->username : '' ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?= $this->theme->baseUrl ?>/dist/img/ebz_logo.png" class="img-circle" alt="User Image">
                                        <p>
                                            <?= Yii::$app->user->identity->username ?>
                                            <small>Admin User since <?= (isset(Yii::$app->user->identity)) ? Yii::$app->formatter->asDate(Yii::$app->user->identity->create_time) : '' ?></small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                <!--         <div class="pull-left">
                                            <a href="<?= Yii::$app->urlManager->createUrl('site/profile') ?>" class="btn btn-default btn-flat">Profile</a>
                                        </div> -->
                                        <div class="pull-right">
                                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('site/logout') ?>" data-method="post" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                      <!--       <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li> -->
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <!-- <div class="user-panel"> -->
                        <!-- <div class="pull-left image">
                            <img src="<?= Yii::$app->params['imageUrl'] ?>default.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p>Elvis Sonatha</p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div> -->
                    <!-- </div> -->
                    <?= $this->render('//layouts/menu') ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?= $this->render('_notification') ?>
                <?php echo $content; ?>
            </div><!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 1.0.0
                </div>
                <strong>Copyright &copy; <?= date('Y') ?> Ebizu Backend Service.</strong> All rights reserved.
            </footer>
            <!-- Add the sidebar's background. This div must be placed
                immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div><!-- ./wrapper -->
        <?php $this->endBody(); ?>
    </body>

    <?php
    $this->registerJs("
        var baseUrl = '" . Yii::$app->homeUrl . "';
    //     $.widget.bridge('uibutton', $.ui.button);
    ", yii\web\View::POS_BEGIN, 'main');
    ?>
</html>
<?php $this->endPage(); ?>
