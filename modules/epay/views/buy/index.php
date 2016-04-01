<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

$this->title = 'Epay Transaction';
$dataProvider->sort->defaultOrder = ['epa_datetime' => SORT_DESC];
$dataProvider->sort->attributes['reward.vou_reward_name'] = [
    'asc' => ['reward.vou_reward_name' => SORT_ASC],
    'desc' => ['reward.vou_reward_name' => SORT_DESC],
];
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?> List</h3>
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input value="<?= (!empty($_GET['search']) ? $_GET['search'] : '') ?>" type="text" name="search" class="form-control input-sm" id="filtersearch" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="You can search by Admin Name, Reward, Quantity, Success Qty & Failed Qty">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="mailbox-controls">
                        <?= Html::a('<i class="fa fa-plus-square"></i> New Buy', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
                    </div>
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'epay',
                            'dataProvider' => $dataProvider,
                            'layout' => '{items}{summary}{pager}',
                            'columns' => [
                                'epa_admin_name',
                                'reward.vou_reward_name',
                                [
                                    'attribute' => 'epa_datetime',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDatetime($data->epa_datetime, "php:d M Y H:i:s");
                                    }
                                ],                                        
                                [
                                    'attribute' => 'epa_qty',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return $data->epa_qty;
                                    }
                                ],                                                                                
                                [
                                    'attribute' => 'epa_success_qty',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return $data->epa_success_qty;
                                    }
                                ],                                                                                
                                [
                                    'attribute' => 'epa_failed_qty',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return $data->epa_failed_qty;
                                    }
                                ],
                                [
                                    'attribute' => 'voucherBought.vob_status',
                                    'header' => 'Status',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->voucherBought))
                                            return $data->voucherBought->vob_status == 0 ? '<i class="btn btn-warning btn-xs">In Progress</i>' : '<i class="btn btn-success btn-xs">Done</i>';
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right">{sell} {detail}</span>',
                                    'buttons' => [
                                        'sell' => function($url, $model) {
                                            if(!empty($model->voucherBought)) {
                                                if($model->voucherBought->vob_status == 1)
                                                    return Html::a('<i class="fa fa-check"></i>', 'javascript:;', ['data-id' => $model->epa_id, 'class' => 'sell btn btn-warning btn-xs']);
                                            }
                                        },
                                        'detail' => function($url, $model) {
                                            return Html::a('
                                                <i class="fa fa-caret-square-o-down"></i> Detail
                                            ', [
                                                'buy/view/?id=' . $model->epa_id
                                            ], ['class' => 'btn btn-info btn-xs']);
                                        }
                                    ]
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]);
                        ?>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>

<?php
Modal::begin([
    'id' => 'ready-view',
    'header' => '<h4>Ready to Sell</h4>',
    'footer' => Html::a('<i class="fa fa-check"></i> Save', 'javascript:;', ['id' => 'save-sell', 'class' => 'btn btn-primary btn-sm'])
]);
?>
<div class="form-group">
    <input type="hidden" name="epa_id" id="epa_id">
    <label for="vob_ready_to_sell">
        <input type="checkbox" id="vob_ready_to_sell"> Ready to Sell
    </label>
</div>
<?php Modal::end(); ?>

<?php
$this->registerJs("
    $('#filtersearch').popover();
    $('#filtersearch').on('keypress', function(ev) {
        if(ev.which == 13) {
            window.location = baseUrl + 'epay/buy?search=' + $(this).val();
        }
    });

    $('.sell').click(function() {
        var id = $(this).data('id');
        $('#epa_id').val(id);
        $('#ready-view').modal('show');
    });

    $('#save-sell').click(function() {
        var epa_id = $('#epa_id').val(),
            vob_ready_to_sell = $('#vob_ready_to_sell').is(':checked') ? 1 : 0;

        $.ajax({
            method: 'POST',
            url: baseUrl + 'epay/buy/sell',
            data: {
                epa_id: epa_id,
                vob_ready_to_sell: vob_ready_to_sell
            },
            cache: false,
            dataType: 'json',
            success: function(result) {
                window.location = result.url
            }
        });
    });

    $('.delete-confirm').on('click', function(){
        var href = $(this).attr('href');
        if(confirm('Are you sure want to delete this data?')){
            return true;
        } else {
            return false;
        }
    });    
", yii\web\View::POS_END, 'epay-buy');
?>
