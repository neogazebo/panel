<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Detail Epay Transaction';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="wrap">
    <div class="container">
        <div id="page-heading">
            <h1><i class="fa fa-credit-card"></i> <?= $this->title ?></h1>
            <div class="options">
                <i class="fa fa-search"></i>
                <input value="<?= (!empty($_GET['search']) ? $_GET['search'] : '') ?>" type="text" name="search" class="" id="filtersearch"> 
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-indigo">
                    <div class="panel-heading">
                        <h4><i class="fa fa-credit-card"></i> <?= $this->title ?> List</h4>
                    </div>
                    <div class="panel-body collapse in">
                        <?= Html::a('<i class="fa fa-long-arrow-left"></i> <span>' . Yii::t('app', 'Back') . '</span>', ['index'], ['class' => 'pull-right btn btn-default']) ?>
                        <?php
                        echo GridView::widget([
                            'id' => 'epay',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'header' => 'Transaction Time',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return substr($data->epd_trans_datetime, 0, 4) . '-' . substr($data->epd_trans_datetime, 4, 2) . '-' . substr($data->epd_trans_datetime, 6, 2) . '&nbsp;' . substr($data->epd_trans_datetime, 8, 2) . ':' . substr($data->epd_trans_datetime, 10, 2) . ':' . substr($data->epd_trans_datetime, 12, 2);
                                    }
                                ],
                                [
                                    'header' => 'Product',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return $data->epd_product_code;
                                    }
                                ],                                        
                                [
                                    'header' => 'Amount',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return $data->epd_amount;
                                    }
                                ],
                                [
                                    'header' => 'Operator ID',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return $data->epd_operator_id;
                                    }
                                ],
                                        
                                [
                                    'header' => 'Merchant ID',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return $data->epd_merchant_id;
                                    }
                                ],
                                [
                                    'header' => 'Terminal ID',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return $data->epd_terminal_id;
                                    }
                                ],
                                [
                                    'header' => 'Transaction Ref',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return $data->epd_ret_trans_ref;
                                    }
                                ],
                                [
                                    'header' => 'Status',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return $data->boughtDetail->vou_redeemed > 0 ? 'Redeemed' : 'Not Redeemed';
                                    }
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped']
                        ]);
                        ?>
                        <!--end table-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    var baseUrl = '".Yii::$app->homeUrl."';
    var id = ".$id.";
    $('#filtersearch').on('keypress', function(ev) {
        if(ev.which == 13) {
            window.location = baseUrl + 'epay/buy/view/?id=' + id + '&search=' + $(this).val();
        }
    });
", yii\web\View::POS_END, 'epay-buy');
?>
