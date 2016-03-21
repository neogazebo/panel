<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Epay Transaction';
$dataProvider->sort->defaultOrder = ['epa_datetime' => SORT_DESC];
?>

<div id="wrap">
    <div class="container">
        <div id="page-heading">
            <h1><i class="fa fa-credit-card"></i> <?= $this->title ?></h1>
            <div class="options" style="margin-top: 15px">
                <i class="fa fa-search"></i>
                <input value="<?= (!empty($_GET['search']) ? $_GET['search'] : '') ?>" type="text" name="search" class="" id="filtersearch" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="You can search by Admin Name, Reward, Quantity, Success Qty & Failed Qty"> 
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-indigo">
                    <div class="panel-heading">
                        <h4><?= $this->title ?> List</h4>
                    </div>
                    <div class="panel-body collapse in table-responsive">
                        <?= Html::a('<i class="fa fa-plus-square"></i> <span>' . Yii::t('app', 'New Epay Voucher') . '</span>', ['create'], ['class' => 'pull-right btn btn-primary']) ?>
                        <?= GridView::widget([
                            'id' => 'epay',
                            'dataProvider' => $dataProvider,
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
                                    'class' => 'yii\grid\DataColumn',
                                    'header' => '',
                                    'format' => 'raw',
                                    'options' => ['style'=>'width:5%'],
                                    'value' => function($data) {
                                        return Html::a('<i class="fa fa-caret-square-o-down"></i> Detail',['buy/view/?id='.$data->epa_id],['class'=>'btn btn-info btn-xs']);
                                    },
                                ],                                        
                            ],
                            'tableOptions' => ['class' => 'table table-striped']
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
	$('#filtersearch').popover();
    var baseUrl = '".Yii::$app->homeUrl."';
    $('#filtersearch').on('keypress', function(ev) {
        if(ev.which == 13) {
            window.location = baseUrl + 'epay/buy/?search=' + $(this).val();
        }
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
