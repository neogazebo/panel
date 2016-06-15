<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SnapEarn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = ($model->isNewRecord ? 'New' : 'Edit') . ' Snap & Earn';
$this->registerJsFile('https://maps.google.com/maps/api/js?sensor=true', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/gmaps/gmaps.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$latitude = ($model->company->com_latitude ? $model->company->com_latitude : 3.139003);
$longitude = ($model->company->com_longitude ? $model->company->com_longitude : 101.686855);
$this->registerCss("
    .datetimepicker-dropdown-bottom-right {
        right: 200px;
    }
");

?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <div class="box-tools">

                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php
                        $form = ActiveForm::begin([
                            'id'=>'snapearn-form',
                            'options' => ['class' => 'form-horizontal'],
                            'enableClientValidation'=>true,
                            'enableAjaxValidation'=>true,
                            'fieldConfig' => [
                                'template' => "{label}\n<div class=\"col-lg-6\">{input}\n<div>{error}</div></div>",
                                'labelOptions' => ['class' => 'col-lg-3 control-label'],
                            ],
                        ]);
                    ?>

                    <div class="panel-body">
                        <div class="col-sm-6">
                            <div id="sna_image" class="form-group magic-zoom"></div>
                        </div>
                        <div class="col-sm-6">
                            <?php if(empty($model->business)): ?>
                                <?php if(!empty($model->newSuggestion)): ?>
                                    <?= $form->field($model->newSuggestion, 'cos_name')->textInput(['readonly' => true])->label('Suggest Merchant'); ?>
                                    <?= $form->field($model->newSuggestion, 'cos_mall')->textInput(['readonly' => true])->label('Suggest Mall'); ?>
                                    <?= $form->field($model->newSuggestion, 'cos_location')->textInput(['readonly' => true]); ?>
                                <?php endif ?>
                            <div class="form-group">
                                <label for="" class="col-lg-3 control-label">Merchant</label>
                                <?php if(!empty($model->newSuggestion)): ?>
                                    <?= Html::a('<i class="fa fa-plus-square"></i> Add New Merchant', Url::to(['new-merchant?id=' . $model->sna_id]), $options = ['class' => 'btn btn-primary btn-sm','target' => '_blank']) ?>
                                <?= Html::button('<i class="fa fa-plus-square"></i> Add Existing Merchant', ['type' => 'button','value' => Url::to(['ajax-existing?id=' . $model->sna_id]), 'class' => 'modalButton btn btn-success btn-sm exs_m']); ?>
                                <?php endif ?>
                            </div>
                            <?php else : ?>
                                <?= $form->field($model, 'sna_business')->textInput(['value' => is_object($model->business) ? $model->business->com_name : '', 'readonly' => true])->label('Merchant') ?>
                                <?php if(is_object($model->business) && $model->business->com_premium == 1): ?>
                                    <?= $form->field($model->business, 'com_premium')->checkBox(['style' => 'margin-top: 10px', 'disabled' => 'disabled'], false)->label('Premium Merchant') ?>
                                <?php endif ?>

                                <?= $form->field($model->business, 'com_point')->textInput(['value' => is_object($model->business) ? $model->business->com_point : 0, 'readonly' => true, 'data-toggle' => 'popover', 'data-trigger' => 'hover', 'data-placement' => 'right', 'data-content' => 'Business Point'])->label('Point Merchant') ?>

                            <?php endif ?>
                            <?php if(Yii::$app->user->identity->level == 1): ?>
                                <?= $form->field($model, 'sna_acc_id')->textInput(['value' => is_object($model->member) ? $model->member->acc_screen_name : '', 'readonly' => true]) ?>
                            <?php endif;?>
                            <?php if(Yii::$app->user->identity->superuser == 1): ?>
                                <div class="form-group field-snapearn-sna_upload_date">
                                <label class="col-lg-3 control-label" >Facebook Email</label>
                                <div class="col-lg-6">
                                    <div class="form-control" readonly="true"><?= !empty($model->member) ? $model->member->acc_facebook_email : '' ?></div>
                                    <div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <?php endif ?>

                            <div class="form-group field-snapearn-sna_upload_date">
                                <label class="col-lg-3 control-label" >Upload on</label>
                                <div class="col-lg-6">
                                    <div class="form-control" readonly="true"><?= $model->sna_upload_date ?></div>
                                    <div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>

                            <?= $form->field($model, 'sna_status')->dropDownList($model->status, ['class' => 'form-control status']) ?>

                            <?= Html::activeHiddenInput($model, 'sna_acc_id') ?>
                            <?= Html::activeHiddenInput($model, 'sna_com_id') ?>
                            <div class="point-form">
                                <?=
                                    $form->field($model, 'sna_transaction_time')->widget(kartik\widgets\DateTimePicker::classname(), [
                                        'options' => ['placeholder' => 'Transaction Time ...'],
                                        'convertFormat' => true,
                                        'value' => $model->sna_upload_date,
                                        'pluginOptions' => [
                                            'format' => 'Y-m-d H:i:s'
                                        ]
                                    ]);
                                ?>                
                                <?= $form->field($model, 'sna_receipt_number')->textInput(['class' => 'form-control sna_status']) ?>
                                <?= $form->field($model, 'sna_receipt_amount')->textInput(['class' => 'form-control sna_amount']) ?>
                                <?= $form->field($model, 'sna_point')->textInput(['class' => 'form-control sna_point', 'readonly' => true]) ?>
                            </div>
                            <div class="reject-form">
                                <?= $form->field($model, 'sna_sem_id')->dropDownList($model->email, ['id' => 'email', 'class' => 'form-control']) ?>
                            </div>
                            <?= $form->field($model, 'sna_push')->checkBox(['style' => 'margin-top: 10px;'], false)->label('Push Notification?') ?>
                            <div class="row">
                                <div class="button-right pull-right">
                                    <button type="submit" class="btn-primary btn submit-button"><i class="fa fa-check"></i> Save</button>
                                    <button class="btn btn-success saveNext" type="submit" name="save-next"><i class="fa fa-arrow-right"></i> Save &amp; Next</button>
                                    <input id="saveNext" type="hidden" name="saveNext" value="">
                                </div>
                                <div class="button-left pull-left">
                                    <?= Html::a('<i class="fa fa-times"></i> Cancel', ['default/cancel?id='.$model->sna_id], ['class' => 'btn btn-default']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- widget to create render modal -->
<?php
    Modal::begin([
        'header' => '</button><h4 class="modal-title"></h4>',
        'id' => 'modal',
        'size' => 'modal-md',
    ]);
?>
<div id="modalContent"></div>
<?php Modal::end(); ?>

<?php
$this->registerCss("
    #sna_image {
        position: relative;
        top: 0;
        left: 0;
        transition: width 0.3s ease,height 0.3s ease,left 0.3s ease,top 0.3s ease;
        -webkit-transition: width 0.3s ease,height 0.3s ease,left 0.3s ease,top 0.3s ease;
        -o-transition: width 0.3s ease,height 0.3s ease,left 0.3s ease,top 0.3s ease;
        -moz-transition: width 0.3s ease,height 0.3s ease,left 0.3s ease,top 0.3s ease;
        z-index: 999;
    }
    .magic-zoom {
        position: relative;
        width: 100%;
        height: 500px;
        overflow: hidden;
        border: 1px solid #ddd;
    }
    .iviewer_common {
        position: absolute;
        bottom: 10px;
        border: 1px solid #999;
        height: 28px;
        z-index: 5000;
    }
    .point-form { display: none; }
    .reject-form { display: none; }
");

$this->registerCssFile($this->theme->baseUrl . '/plugins/perfect-zoom/jquery.iviewer.css', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/perfect-zoom/src/jquery.mousewheel.min.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/perfect-zoom/jquery.iviewer.min.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$imageSource = Yii::$app->params['businessUrl'] . 'receipt/' . $model->sna_receipt_image;
$this->registerJs("
    var id = '" . $model->sna_id . "',
        com_id = '" . $model->sna_com_id . "';

    $('#sna_image').iviewer({
        src: '".$imageSource."'
    });
$('#snapearn-sna_transaction_time').attr('autofocus');
    $('#snapearn-sna_status').change(function() {
        if($(this).val() == 1) {
            $('#snapearn-sna_transaction_time').attr('autofocus');
            $('.reject-form').css('display', 'none');
            $('.point-form').css('display', 'block');
        } else if($(this).val() == 2) {
            $('.point-form').css('display', 'none');
            $('.reject-form').css('display', 'block');
        } else {
            $('.reject-form').css('display', 'none');
            $('.point-form').css('display', 'none');
        }
    }).trigger('change');

    $('#snapearn-sna_receipt_amount').blur(function() {
        var amount = Math.floor($('#snapearn-sna_receipt_amount').val());
        // $('#snapearn-sna_point').val(point);
        $.ajax({
            type: 'POST',
            url: baseUrl + 'snapearn/default/ajax-snapearn-point',
            data: { id: id, com_id: com_id, amount: amount },
            dataType: 'json',
            success: function(result) {
                $('#snapearn-sna_point').val(result);
            }
        });
    });

    $('.saveNext').click(function(){
        $('#saveNext').val(1);
    });
    $('.submit-button, .reset-button').click(function(){
        $('#saveNext').val(0);
    });
    
    $('.new_m').on('click',function(){
        $('.modal-dialog').switchClass( 'modal-md', 'modal-lg');
    });

    $('.exs_m').on('click',function(){
        $('.modal-dialog').switchClass( 'modal-lg','modal-md');
    });

", yii\web\View::POS_END, 'snapearn-form');
?>
