<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Epay Transaction';
$dataProvider->sort->defaultOrder = ['epa_datetime' => SORT_DESC];
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?= $this->title ?> List</h3>
             <?= Html::a('<i class="fa fa-plus-square"></i> <span>' . Yii::t('app', 'Create') . '</span>', ['create'], ['class' => 'btn btn-sm btn-primary','style'=>'margin-left: 20px;']) ?>
          <div class="box-tools">
            <div class="input-group">
                <input value="<?= (!empty($_GET['search']) ? $_GET['search'] : '') ?>" type="text" name="search" class="form-control input-sm pull-right" id="filtersearch" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="You can search by Admin Name, Reward, Quantity, Success Qty & Failed Qty">
              <div class="input-group-btn">
                <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <?= GridView::widget([
                'id' => 'epay',
                'dataProvider' => $dataProvider,
                'summary'=>'',
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
