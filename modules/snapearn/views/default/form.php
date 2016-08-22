<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SnapEarn;
use app\components\helpers\Utc;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\widgets\DateTimePicker;
use kartik\money\MaskMoney;

$this->title = "Update Snap & Earn";
$model->sna_push = true;
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <?php
    $form = ActiveForm::begin([
        'id' => 'snapearn-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'fieldConfig' => [
        'template' => "{label}\n<div class=\"\">{input}\n<div>{error}</div></div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <!-- Box Comment -->
            <div class="box box-widget">
                <div class="box-header with-border">
                    <?php if (Yii::$app->user->identity->level == 1 || Yii::$app->user->identity->superuser == 1) : ?>
                    <div class="user-block">
                        <img class="img-circle" src="<?= (!empty($model->member->acc_photo)) ? Yii::$app->params['memberUrl'].$model->member->acc_photo : $this->theme->baseUrl.'/dist/img/manis.png'?>" alt="<?= (!empty($model->member)) ? $model->member->acc_screen_name : '<a class=""><span class="not-set">(not set)</span></a>' ?>">
                        <span class="username">
                            <a href="/account/default/view?id=<?= $model->member->acc_id ?>" target="_blank">
                                <?= (!empty($model->member)) ? $model->member->acc_screen_name : '<a class=""><span class="not-set">(not set)</span></a>' ?>
                            </a>
                        </span>
                        <span class="description text-green">Receipt Upload : <?=  Yii::$app->formatter->asDateTime($model->sna_upload_date, 'php:d M Y H:i:s') ?></span>
                    </div>
                    <?php else : ?>
                    <div class="user-block">
                        <img class="img-circle" src="<?= $this->theme->baseUrl.'/dist/img/manis.png'?>" alt="manis receipt">
                        <span class="username">
                            <a href="#">
                                Detail Receipt
                            </a>
                        </span>
                        <span class="description text-green">Receipt Upload : <?= Yii::$app->formatter->asDateTime($model->sna_upload_date, 'php:d M Y H:i:s') ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div style="display: block;" class="box-body">
                    <div id="sna_image" class="img-responsive pad magic-zoom"></div>
                </div>
                <div style="display: block;" class="box-footer no-padding">

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- general form elements disabled -->
            <div class="box box-widget">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Approval</h3>
                    <?php if (empty($model->merchant)): ?>
                    <div class="pull-right btn-merchant">
                        <?= Html::a('<i class="fa fa-plus-square"></i> Add New Merchant', Url::to(['new-merchant?id=' . $model->sna_id]), $options = ['class' => 'btn btn-flat btn-primary btn-xs','target' => '_blank']) ?>
                        <?= Html::button('<i class="fa fa-plus-square"></i> Add Existing Merchant', ['type' => 'button','value' => Url::to(['ajax-existing?id=' . $model->sna_id]), 'class' => 'modalButton btn btn-flat btn-warning btn-xs']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="box-body">
                    <ul class="nav nav-stacked update">
                        <?php if(Yii::$app->user->identity->superuser == 1): ?>
                        <li class="">
                            <a href="#"><b>Facebook Email </b> <span class="pull-right text-light-blue"><?= (!empty($model->member)) ? $model->member->acc_facebook_email : ' - ' ?></span></a>
                        </li>
                        <?php endif; ?>

                        <li class="">
                            <a href="#"><b><?= (empty($model->merchant)) ? 'Suggestion Merchant' : 'Merchant' ?></b> <span class="pull-right text-light-blue"><?= (empty($model->merchant)) ? (empty($model->newSuggestion)) ? '' : $model->newSuggestion->cos_name : $model->merchant->com_name ?></span></a>
                        </li>

                        <?php if (!empty($model->merchant)) : ?>
                        <li class="">
                            <a href="#"><b>Merchant Point</b>

                                <!--start-->
                                <?php if ($model->merchant->com_point < 1000) :?>

                                    <!--this validation if add point to merchant is only specific user-->
                                    <?php // if (Yii::$app->user->identity->level == 1 || Yii::$app->user->identity->superuser == 1) : ?>
                                    <?= Html::button('<i class="fa fa-plus-square"></i> Add Point', ['type' => 'button','value' => Url::to(['short-point?id=' . $model->sna_com_id]).'&&sna_id='.$model->sna_id, 'class' => 'modalButton btn btn-flat btn-warning btn-xs add-point']); ?>
                                    <?php // else: ?>
                                    <!--<span class="label label-warning add-point">Point is less than 500!</span>-->
                                    <?php // endif; ?>

                                <?php endif; ?>
                                <!--end--> 

                                <span class="pull-right text-light-blue">
                                    <?= (!empty($model->merchant)) ? $model->merchant->com_point : '' ?>
                                </span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (empty($model->merchant)) : ?>
                        <li>
                            <a href="#"><b><?= (empty($model->sna_address)) ? 'Suggestion Location' : 'Location' ?></b>
                                <span class="pull-right text-light-blue"><?= (empty($model->sna_address)) ? $model->newSuggestion->cos_location : $model->sna_address ?></span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <li></li>
                    </ul>

                    <?= $form->field($model, 'sna_status')->dropDownList($model->status, ['class' => 'form-control status']) ?>
                    <?= Html::activeHiddenInput($model, 'sna_acc_id') ?>
                    <div class="point-form">
                        <?=
                        $form->field($model, 'sna_transaction_time')->widget(DateTimePicker::classname(), [
                            'options' => [
                                'value' => Yii::$app->formatter->asDateTime($model->sna_upload_date,'php: Y-m-d H:i:s')
                            ],
                            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd hh:i:ss',
                                'endDate' => date('Y-m-d 23:59:59')
                            ]
                        ]);
                        ?>

                        <?php //$form->field($model, 'sna_receipt_number')->textInput(['class' => 'form-control sna_status','value' => '','placeholder' => $model->sna_receipt_number]) ?>
                        <?= $form->field($model, 'sna_ops_receipt_number')->textInput(['class' => 'form-control sna_status'])->label('Receipt No. / Invoice No. / Bill No. / Doc. No. / Transaction No.') ?>
                        <?= $form->field($model, 'sna_receipt_amount')->widget(MaskMoney::classname(['class' => 'form-control sna_amount']))?>
                        <?= $form->field($model, 'sna_point')->textInput(['class' => 'form-control sna_point', 'readonly' => true]) ?>
                    </div>
                    <div class="reject-form">
                        <?= $form->field($model, 'sna_sem_id')->dropDownList($model->email, ['id' => 'email', 'class' => 'form-control']) ?>
                    </div>
                    <?= $form->field($model, 'sna_push')->checkBox(['style' => 'margin-top: 10px;','value' => 1])->label('Push Notification?') ?>
                    <?= $form->field($model, 'sna_com_id')->hiddenInput(['style' => 'display:none'])->label('') ?>
                    <div class="box-footer clearfix">
                        <div class="button-right pull-right">
                            <button type="submit" class="btn-primary btn submit-button"><i class="fa fa-check"></i> Save</button>
                            <button class="btn btn-success saveNext" type="submit" name="save-next"><i class="fa fa-arrow-right"></i> Save &amp; Next</button>
                            <input id="saveNext" type="hidden" name="saveNext" value="">
                        </div>
                        <div class="button-left pull-left">
                            <?= Html::a('<i class="fa fa-times"></i> Cancel', [Url::previous()], ['class' => 'btn btn-default']) ?>
                        </div>
                    </div>
                    <input type="hidden" name="parent" class="parent" value="1">
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</section>

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
        height: 480px;
        overflow: hidden;
        border: 1px solid #ddd;
        background-color: #ECF0F5;
    }
    .iviewer_common {
        position: absolute;
        bottom: 10px;
        border: 1px solid #999;
        height: 28px;
        z-index: 5000;
    }
    .nav-stacked {
        padding: 0px 0px 10px 0px;
    }
    .nav-stacked > li > a {
        border-radius: 0;
        border-top: 0;
        border-left: 0px solid transparent;
        color: #444;
    }
    .nav.update > li > a {
        position: relative;
        display: block;
        padding: 10px 0px;
    }
    .form-div {
        padding-top: 10px;
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
        $('.field-snapearn-sna_receipt_amount').removeClass('has-error');
        $('.btn-merchant').css({
            'border':'0px',
            'padding':'0px'
        });
        $('.field-snapearn-sna_receipt_amount').find('.help-block').text('');

        if($(this).val() == 1) {
            pointConvert();
            $('.tooltip').show().tooltipster();

            if (com_id == 0) {
                $('.field-snapearn-sna_receipt_amount').addClass('has-error');
                $('.btn-merchant').css({
                    'border':'1px solid #DD4F3E',
                    'padding':'2px'
                });
                $('.field-snapearn-sna_receipt_amount').find('.help-block').text('Please create merchant first! Thanks.');
            }

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

    $('#snapearn-sna_receipt_amount').change(function(){
        pointConvert();
    });

    function pointConvert() {
        var amount = Math.floor($('#snapearn-sna_receipt_amount').val());
        $.ajax({
            type: 'POST',
            url: baseUrl + 'snapearn/default/ajax-snapearn-point',
            data: { id: id, com_id: com_id, amount: amount },
            dataType: 'json',
            success: function(result) {
                $('#snapearn-sna_point').val(result);
            }
        });
    }

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
