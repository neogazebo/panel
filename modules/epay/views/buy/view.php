<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Detail Epay Transaction';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
    <?= Html::a('<i class="fa fa-long-arrow-left"></i> <span>' . Yii::t('app', 'Back') . '</span>', ['index'],['class'=>'btn btn-sm btn-default','style'=>'margin: 10px 0px;']) ?>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?= $this->title ?> List</h3>
          <div class="box-tools">
            <div class="input-group">
                <input value="<?= (!empty($_GET['search']) ? $_GET['search'] : '') ?>" type="text" name="search" class="form-control input-sm" id="filtersearch"> 
              <div class="input-group-btn">
                <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <?php
            echo GridView::widget([
                'id' => 'epay',
                'dataProvider' => $dataProvider,
                'summary'=>'',
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
                'tableOptions' => ['class' => 'table table-hover']
            ]);
            ?>

        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  </div>
</section>
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
