<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SnapEarn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\widgets\DateTimePicker;

$this->title = "Update SnapEarn";
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
<?php
    $form = ActiveForm::begin([
        'id'=>'snapearn-form',
        // 'options' => ['class' => 'form-horizontal'],
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
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
              <img class="img-circle" src="<?= (!empty($model->member->acc_photo)) ? Yii::$app->params['memberUrl'].$model->member->acc_photo : $this->theme->baseUrl.'/dist/img/manis.png'?>" alt="<?= $model->member->acc_screen_name ?>">
              <span class="username">
                <a href="#">
                  <?= $model->member->acc_screen_name ?>
                </a>
              </span>
              <span class="description text-green">Receipt Upload : <?=  Yii::$app->formatter->asDateTime($model->sna_upload_date) ?></span>
            </div>
            <?php else : ?>
              <div class="user-block">
               <img class="img-circle" src="<?= $this->theme->baseUrl.'/dist/img/manis.png'?>" alt="manis receipt">
              <span class="username">
                <a href="#">
                  Detail Receipt
                </a>
                </span>
                <span class="description text-green">Receipt Upload : <?= Yii::$app->formatter->asDateTime($model->sna_receipt_date) ?></span>
              </div>
            <?php endif; ?>
            <div class="box-tools">
             <!--  <button data-original-title="Mark as read" class="btn btn-box-tool" data-toggle="tooltip" title=""><i class="fa fa-circle-o"></i></button>
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
            </div><!-- /.box-tools -->
          </div><!-- /.box-header -->
          <div style="display: block;" class="box-body">
              <div id="sna_image" class="img-responsive pad magic-zoom"></div>
          </div>
          <div style="display: block;" class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <?php if(Yii::$app->user->identity->superuser == 1): ?>
                <li class="">
                  <a href="#"><b>Facebook Email </b> <span class="pull-right text-light-blue"><?= (!empty($model->member)) ? $model->member->acc_facebook_email : ' - ' ?></span></a>
                </li>
                <?php endif; ?>
                <li class="">
                  <a href="#"><b><?= (empty($model->business)) ? 'Sugest Merchant' : 'Merchant' ?></b> <span class="pull-right text-light-blue"><?= (!empty($model->newSuggestion)) ? $model->newSuggestion->cos_name : $model->business->com_name ?></span></a>
                </li>
                <li class="">
                  <a href="#"><b>Merchant Point</b>
                    <?php if (!empty($model->business) && $model->business->com_point < 500) :?> 
                      <?php if (Yii::$app->user->identity->level == 1 || Yii::$app->user->identity->superuser == 1) : ?>
                        <?= Html::button('<i class="fa fa-plus-square"></i> Add Point', ['type' => 'button','value' => Url::to(['short-point?id=' . $model->sna_com_id]).'&&sna_id='.$model->sna_id, 'class' => 'modalButton btn btn-flat btn-warning btn-xs add-point']); ?> 
                      <?php else: ?>
                        <span class="label label-warning add-point">Point is less than 500!</span>
                      <?php endif; ?>
                    <?php endif; ?>
                    <span class="pull-right text-light-blue">
                      <?= (!empty($model->business)) ? $model->business->com_point : '' ?>
                    </span>
                  </a>
                </li>
                <li>
                  <a href="#"><b>Sugest Merchant</b>
                    <span class="pull-right text-light-blue"><?= $model->newSuggestion->cos_name ?></span>
                  </a>
                </li>
              </ul>
          </div>
        </div>
      </div>


    <div class="col-md-6">
              <!-- general form elements disabled -->
              <div class="box box-widget">
                <div class="box-header with-border">
                  <h3 class="box-title">Form Approval</h3>
                  <?php if (empty($model->business)) : ?>
                  <div class="pull-right btn-merchant">
                    <?= Html::a('<i class="fa fa-plus-square"></i> Add New Merchant', Url::to(['new-merchant?id=' . $model->sna_id]), $options = ['class' => 'btn btn-flat btn-primary btn-xs','target' => '_blank']) ?>
                    <?= Html::button('<i class="fa fa-plus-square"></i> Add Existing Merchant', ['type' => 'button','value' => Url::to(['ajax-existing?id=' . $model->sna_id]), 'class' => 'modalButton btn btn-flat btn-warning btn-xs']); ?> 
                  </div>
                  <?php endif; ?>
                </div>
                <div class="box-body">
                  <form role="form">
                    <?= $form->field($model, 'sna_status')->dropDownList($model->status, ['class' => 'form-control status']) ?>
                    <?= Html::activeHiddenInput($model, 'sna_acc_id') ?>
                    <?= Html::activeHiddenInput($model, 'sna_com_id') ?>
                    <div class="point-form">
                       <?=
                          $form->field($model, 'sna_transaction_time')->widget(DateTimePicker::classname(), [
                              'options' => [
                                  'value' => Yii::$app->formatter->asDateTime($model->sna_upload_date,'php: Y-m-d H:i:s')
                              ],
                              'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
                              'pluginOptions' => [
                                  'autoclose'=>true,
                                  'format' => 'yyyy-mm-dd H:i:s'
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
                    <div class="box-footer clearfix">
                      <div class="button-right pull-right">
                          <button type="submit" class="btn-primary btn submit-button"><i class="fa fa-check"></i> Save</button>
                          <button class="btn btn-success saveNext" type="submit" name="save-next"><i class="fa fa-arrow-right"></i> Save &amp; Next</button>
                          <input id="saveNext" type="hidden" name="saveNext" value="">
                      </div>
                      <div class="button-left pull-left">
                          <?= Html::a('<i class="fa fa-times"></i> Cancel', ['default/cancel?id='.$model->sna_id], ['class' => 'btn btn-default']) ?>
                      </div>
                    </div>
                  </form>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
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
        height: 350px;
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

    function pointConvert(){
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