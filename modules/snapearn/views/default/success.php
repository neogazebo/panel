<?php
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = "New Merchant";
// echo Yii::$app->urlManager->hostInfo;exit;
?>

<section class="content-header">
    <h1></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <div class="box-tools">

                      <div class="box box-default">
                        <div class="box-header with-border">
                          <i class="fa fa-warning"></i>
                          <h3 class="box-title">Success</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4>    <i class="icon fa fa-check"></i> Alert!</h4>
                            Success alert preview. This alert is dismissable.
                          </div>
                        </div>
                      </div>

                    </div>
                </div>
                <div class="box-body">
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$this->registerJs("
    window.opener.location.reload(true);
    window.close();
");
?>