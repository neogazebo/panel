<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\modal;
use yii\helpers\Url;

$this->title = 'List Role';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::button('<i class="fa fa-plus-square"></i> Create Role',['id' => 'modalButton','value' => Url::to('create'),'class' => 'btn btn-primary btn-sm']) ?>
                </div><!-- /.box-header -->
                <?php
                    Modal::begin([
                            'header' => '<h4>Create Role</h4>',
                            'id' => 'modal',
                            'size' => 'modal-lg',
                        ]);
                    echo "<div id='modalContent'></div>";
                    Modal::end();
                ?>
                <div class="box-body">
                    <div class="table-responsive">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>