<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

?>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">

                </div><!-- /.box-header -->
                <?php
                    $form = ActiveForm::begin([
                        'id'=>'create-form',
                        'options' => ['class' => 'form-group'],
                        'enableAjaxValidation'=>true
                    ]);
                ?>
                <div class="box-body">
                    <?= $form->field($model,'name') ?>
                    <?= $form->field($model,'description')->textArea() ?>
                </div>
                <div class="box-footer">
                <?= Html::resetButton('Cancel', ['class' => 'btn btn-warning','data-dismiss' => 'modal']) ?>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-info pull-right']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>