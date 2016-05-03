<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;


$this->title = 'Create Role';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">

                </div><!-- /.box-header -->
                <div class="box-body">
                <?php
                    $form = ActiveForm::begin([
                        'id'=>'create-form',
                        'options' => ['class' => 'form-group'],
                        'enableAjaxValidation'=>true,
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-6 input-group\">{input}\n<div>{error}</div></div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ],
                    ]);
                ?>
                    
                    <?= $form->field($model,'name') ?>
                    <?= $form->field($model,'type') ?>
                    <?= $form->field($model,'description')->textArea() ?>
                    <?= $form->field($model,'rule_name') ?>

                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>