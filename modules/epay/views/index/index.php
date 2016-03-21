<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Epay Management';
?>
<div id='wrap'>
    <div class="container">

        <div class="row"> <!-- row-->
            <div class="col-md-12" style="">
                <a id="check-connection" href="javascript:void(0)" class="btn btn-midnightblue"><i class="fa fa-refresh"></i> Test Connection</a>
                <div class="panel panel-primary" style="margin-top: 5px;">
                    <div class="panel-heading">Server Status</div>
                    <div class="panel-body">
                        <div class="">
                            <a id="box-server-info" class="info-tiles tiles-success" href="javascript:void(0)" style="display: none;">
                                <div class="tiles-heading">Connection Information</div>
                                <div class="tiles-body-alt">
                                </div>
                                <div class="tiles-footer"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-heading">Reconciliation</div>
                        <div class="options">

                        </div>
                    </div>
                    <div class="panel-body">
                        <p>Please select reconciliation process below</p>
                        <table class="table table-bordered">
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
                                            //'labelOptions' => ['class' => 'col-lg-3 control-label'],
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
                    </div>
                </div>
            </div>            

        </div><!--end row-->
    </div>
</div>

<?php
$this->registerJs("
    var baseUrl = '" . Yii::$app->homeUrl . "';
    $(document).ready(function() {
        $( '.datepicker' ).datepicker({ 
        dateFormat: 'dd/mm/yy',
        beforeShow: function(i) { if ($(i).attr('readonly')) { return false; } }        
        }).datepicker('setDate', new Date());
    });
    $(function() {
        var box_server_info = $('#box-server-info');
        $('#check-connection').on('click', function(){
            // req
            $.ajax({
                url: baseUrl + 'epay/index/check-connection',
                beforeSend: function( xhr ) {
                    box_server_info.removeClass('tiles-alizarin');
                    box_server_info.removeClass('tiles-success');
                    box_server_info.addClass('tiles-toyo');
                    box_server_info.find('.tiles-body-alt').html('<i class=\'fa fa-download\'></i><div class=\'text-center\'>Checking Connection ...</div>');
                    box_server_info.find('.tiles-footer').empty();
                    box_server_info.show();
                },
                success : function(data) {
                    var data = JSON.parse(data);
                    if(data.status == '00') {
                        box_server_info.removeClass('tiles-toyo');
                        box_server_info.removeClass('tiles-alizarin');
                        box_server_info.addClass('tiles-success');
                        box_server_info.find('.tiles-body-alt').html('<i class=\'fa fa-check\'></i><div class=\'text-center\'>Connected!</div>');
                        box_server_info.find('.tiles-footer').html('<span>Time : </span><span>'+ data.execution_time +' Msc</span>');
                        box_server_info.show();
                    } else {
                        box_server_info.removeClass('tiles-toyo');
                        box_server_info.removeClass('tiles-success');
                        box_server_info.addClass('tiles-alizarin');
                        box_server_info.find('.tiles-body-alt').html('<i class=\'fa fa-comments-o\'></i><div class=\'text-center\'>Connection Failed!</div><small>'+ data.message +'</small>');
                        box_server_info.find('.tiles-footer').html('<span>Time : </span><span>'+ data.execution_time +' Msc</span>');
                        box_server_info.show();
                    }
                }
            })
            .done(function( data ) {});
        });
    });
", yii\web\View::POS_END, 'epay-dashboard-' . time());
?>
