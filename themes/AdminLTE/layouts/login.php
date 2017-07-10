<?php

use yii\helpers\Html;
$this->beginPage();
$this->title = 'Ebizu Redemption Store | Login';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= Html::encode($this->title); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta property='og:site_name' content='<?php echo Html::encode($this->title); ?>' />
        <meta property='og:title' content='<?php echo Html::encode($this->title); ?>' />
        <meta property='og:description' content='<?php echo Html::encode($this->title); ?>' />
        <?php
        $this->registerCssFile($this->theme->baseUrl . "/bootstrap/css/bootstrap.min.css");
        $this->registerCssFile("https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css");
        $this->registerCssFile("https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css");
        $this->registerCssFile($this->theme->baseUrl . "/dist/css/AdminLTE.min.css");
        $this->registerCssFile($this->theme->baseUrl . "/plugins/iCheck/square/blue.css");
        ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php $this->head(); ?>
    </head>

    <body class="hold-transition login-page">
        <?php $this->beginBody(); ?>
        <?php echo $content; ?>
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>
