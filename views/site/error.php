<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\modal;
use yii\web\View;
use yii\helpers\Url;

$this->title = $name;
$image = $this->theme->baseUrl.'/dist/img/photo2.png';

$this->registerCss('
    .box-body {
        min-height: 100%;
    }
    .site-error {
        background: url('.$image.') no-repeat center center fixed; 
         -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        min-height: 100%;
        text-align: center;
        color: #fff;
        padding: 150px 30px;
    }
    .site-error .alert {
        margin : 20px 40px;

    }
');
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body ">
                    <div class="site-error">

                        <h1><?= Html::encode($this->title) ?></h1>

                        <div class="alert alert-danger">
                            <?= nl2br(Html::encode($message)) ?>
                        </div>

                        <p>
                            The above error occurred while the Web server was processing your request.
                        </p>
                        <p>
                            Please contact us if you think this is a server error. Thank you.
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
