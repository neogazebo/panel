<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Reconciliation';
$this->registerCss("
    .info-box-signal{
        display: block;
        float: left;
        text-align: center;
        font-size: 45px;
        color: red;
    }
    .info-box-signal small { font-size: 20px; }
    .info-box-icon { cursor: pointer; }
    .info-box-icon:hover .fa-refresh { color: #00c0ef; }
    .green { color: green; }
    .red { color: red; }
    .fa-spin-2x {
        -webkit-animation: fa-spin 1s infinite linear;
        animation: fa-spin 1s infinite linear;
    }
");
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="info-box">
                <span id="check-connection" class="info-box-icon bg-green">
                    <i class="fa fa-refresh"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Test Connection</span>
                    <span class="info-box-signal">
                        <i id="bip" class="fa fa-signal"></i> 
                        <small></small>
                    </span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>

        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <div class="box-tools"></div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <p>Please select reconciliation process below</p>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Parameter</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Recon All Data & Download to local</td>
                                <td></td>
                                <td>
                                    <?= Html::a('<i class="fa fa-external-link-square"></i> <span>' . Yii::t('app', 'Process') . '</span>', ['recon/manual-recon/?data=all'], ['id' => 'rec-all', 'class' => 'btn btn-xs btn-midnightblue DTTT_button_text']); ?>                                    
                                </td>
                            </tr>
                            <tr>
                                <td>Recon Data Today & Download to local</td>
                                <td></td>
                                <td>
                                    <?= Html::a('<i class="fa fa-external-link-square"></i> <span>' . Yii::t('app', 'Process') . '</span>', ['recon/manual-recon/?data=today'], ['id' => 'rec-today', 'class' => 'btn btn-xs btn-midnightblue DTTT_button_text']); ?>                                    
                                </td>
                            </tr>    
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'category-form',
                                'action' => Yii::$app->urlManager->createUrl(['epay/recon/manual-recon/?data=specific']),
                                'options' => ['class' => 'form-horizontal', 'target' => ''],
                                'fieldConfig' => [],
                            ]);
                            ?>                                
                            <tr>
                                <td>Recon Data Specific Date & Download to local</td>
                                <td><?= Html::textInput('date', '', ['class' => 'datepicker form-control']) ?></td>
                                <td>
                                    <button type="submit" class="btn btn-xs btn-midnightblue DTTT_button_text"><i class="fa fa-external-link-square"></i> <span> Process</button>
                                </td>
                            </tr>                                                                
                            <?php ActiveForm::end(); ?>
                            <tr>
                                <td>Recon Data Today & Upload to Epay Server</td>
                                <td></td>
                                <td>
                                    <?= Html::a('<i class="fa fa-external-link-square"></i> <span>' . Yii::t('app', 'Process') . '</span>', ['recon/ftp'], ['id' => 'rec-today', 'class' => 'btn btn-xs btn-midnightblue DTTT_button_text']); ?>                                    
                                </td>
                            </tr>   
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'ftp-form',
                                'action' => Yii::$app->urlManager->createUrl(['epay/recon/ftp']),
                                'options' => ['class' => 'form-horizontal', 'target' => ''],
                                'fieldConfig' => [
                                'template' => "{input}\n{error}",
                                    // 'labelOptions' => ['class' => 'col-lg-3 control-label'],
                                ],
                            ]);
                            ?>                                
                            <tr>
                                <td>Recon Data Specific & Upload to Epay Server</td>
                                <td><?php echo Html::textInput('date', '', ['class' => 'datepicker form-control']) ?></td>
                                <td>
                                    <button type="submit" class="btn btn-xs btn-midnightblue DTTT_button_text"><i class="fa fa-external-link-square"></i> <span> Process</button>
                                </td>
                            </tr>                                                                
                            <?php ActiveForm::end(); ?>                                
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>

<?php
$this->registerJs("
var timer = setInterval(function() {
    $('#bip').toggleClass('green');
}, 500);


$( '.datepicker' ).datepicker({ 
    dateFormat: 'dd/mm/yy',
    beforeShow: function(i) { if ($(i).attr('readonly')) { return false; } }        
}).datepicker('setDate', new Date());

var box_server_info = $('#box-server-info');
$('#check-connection').on('click', function(){
    $('.fa-refresh').addClass('fa-spin');
    swal({
      title: 'Test Connection',
      text: 'Submit to test connection',
      type: 'info',
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
    },function(){
        $.ajax({
            url: baseUrl + 'epay/index/check-connection',
            success : function(data) {
                var data = JSON.parse(data);
                if(data.status == '00') {
                    swal('Connected!', 'Connection success.', 'success');
                    clearInterval(timer);
                    $('#bip').addClass('green');
                    $('.fa-refresh').removeClass('fa-spin');
                    $('.info-box-signal small').text('Connected!');
                } else {
                    swal('Failed!', 'Connection failed.', 'error');
                    clearInterval(timer);
                    $('#bip').removeClass('green');
                    $('.fa-refresh').removeClass('fa-spin');
                    $('.info-box-signal small').text('Connection Fail!');
                    $('.datepicker').datepicker({ 
                        dateFormat: 'dd/mm/yy',
                        beforeShow: function(i) { if ($(i).attr('readonly')) { return false; }}        
                    }).datepicker('setDate', new Date());
                }
            }
        });
    });
        $('button.cancel').click(function(){
            $('.fa-refresh').removeClass('fa-spin');
        });
 });
", yii\web\View::POS_END, 'epay-dashboard-' . time());
?>
