<?php

use gftp\FtpWidget;
$this->title = "Report Transaction";
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
    	<div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <div class="box-tools"></div>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<?= FtpWidget::widget(); ?>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>