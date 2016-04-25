<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

$this->title = 'Epay Transaction List';
$dataProvider->sort->defaultOrder = ['epa_datetime' => SORT_DESC];
$dataProvider->sort->attributes['rewardBought.vob_datetime'] = [
    'asc' => ['rewardBought.vob_datetime' => SORT_ASC],
    'desc' => ['rewardBought.vob_datetime' => SORT_DESC],
];
$dataProvider->sort->attributes['productTitle.epp_title'] = [
    'asc' => ['productTitle.epp_title' => SORT_ASC],
    'desc' => ['productTitle.epp_title' => SORT_DESC],
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
                    <?= Html::a('<i class="fa fa-plus-square"></i> New Buy', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'epay',
                            'dataProvider' => $dataProvider,
                            'layout' => '{items}{summary}{pager}',
                            'columns' => [
                                'epa_admin_name',
                                // [
                                //     'attribute' => 'rewardBought.vob_datetime',
                                //     'format' => 'html',
                                //     'value' => function($data) {
                                //         if(!empty($data->rewardBought))
                                //             return Yii::$app->formatter->asDate($data->rewardBought->vob_datetime);
                                //     }
                                // ],
                                 'productTitle.epp_title',
                                [
                                    'label' => 'Reward',
                                    'format' => 'html',
                                    'value' => function($model, $url){
                                        $vbought = $model->rewardBought;
                                        if(!empty($vbought))
                                            return $vbought->voucher->vou_reward_name;
                                    }
                                ],
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
                                    'attribute' => 'voucherBought.vob_ready_to_sell',
                                    'header' => 'Ready to Sell',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->rewardBought))
                                            return $data->rewardBought->vob_ready_to_sell == 0 ? '<i class="fa fa-circle-o"></i>' : '<i class="fa fa-check"></i>';
                                    }
                                ],
                                [
                                    'attribute' => 'voucherBought.vob_status',
                                    'header' => 'Status',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->rewardBought))
                                            return $data->rewardBought->vob_status == 0 ? '<i class="btn btn-warning btn-xs">In Progress</i>' : '<i class="btn btn-success disabled btn-xs">Done</i>';
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right">{sell} {detail}</span>',
                                    'buttons' => [
                                        'sell' => function($url, $model) {
                                            if(!empty($model->rewardBought)) {
                                                if($model->rewardBought->vob_status == 0){
                                                    return "<span class='btn btn-warning disabled btn-xs'><i class='fa fa-check'></i></span>";
                                                }else{
                                                    return Html::a('<i class="fa fa-check"></i>', 'javascript:;', ['data-id' => $model->epa_id, 'data-qty' => $model->epa_success_qty, 'class' => 'sell btn btn-warning btn-xs']);
                                                }
                                                // if($model->rewardBought->vob_status == 1 && $model->rewardBought->vob_ready_to_sell == 1 ){
                                                //     return "<span class='btn btn-warning disabled btn-xs'><i class='fa fa-check'></i></span>";
                                                // }else{
                                                //     return Html::a('<i class="fa fa-check"></i>', 'javascript:;', ['data-id' => $model->epa_id, 'data-qty' => $model->epa_success_qty, 'class' => 'sell btn btn-warning btn-xs']);
                                                // }
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
        var id = $(this).data('id'),
            qty = $(this).data('qty');

        $('#epa_id').val(id);
        if(qty > 0)
            $('#ready-view').modal('show');
        else
            alert('Epay quantity is Zero!');
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
