<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Voucher Bought Detail '. $voucher->vou_reward_name;
$this->params['breadcrumbs'][] = $this->title;
$imgBase = 'https://d1307f5mo71yg9.cloudfront.net/images/media/web/business/';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="attachment-block clearfix">
                <img class="attachment-img" data-holder-rendered="true" src="<?= ($voucher->vou_image !== null) ? $imgBase.$voucher->vou_image : '' ?>" style="height: 200px; width: 100%; display: block;" data-src="" alt="">
                <div class="attachment-pushed">
                    <div class="attachment-text">
                        <div class="col-md-6">
                            <div class="no-padding">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                <h4 class="attachment-heading"><a href="#"><?= $voucher->vou_reward_name ?></a></h4>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                      <td>Value</td>
                                      <td><span class="badge bg-red"><?= $voucher->vou_value ?></span></td>
                                    </tr>
                                    <tr>
                                      <td>Valid</td>
                                      <td><span class="badge bg-light-blue"><?= Yii::$app->formatter->asDate($voucher->vou_valid_start) ?></span></td>
                                    </tr>
                                    <tr>
                                      <td>Expired</td>
                                      <td><span class="badge bg-yellow"><?= Yii::$app->formatter->asDate($voucher->vou_valid_end)  ?></span></td>
                                    </tr>
                                    <!-- <tr>
                                      <td>Description</td>
                                      <td><?= $voucher->vou_description ?></td>
                                    </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::a('<i class="fa fa-plus-square"></i> <span>' . Yii::t('app', 'Bought Voucher') . '</span>', ['voucher/create'], ['class' => 'btn btn-primary']) ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'voucher-bought',
                            'layout' => '{items}{summary}{pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'header' => '&nbsp;',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        if($data->voucher)
                                            return Html::img($data->voucher->image, ['height' => 32]);
                                    }
                                ],
                                'voucher.vou_reward_name',
                                [
                                    'attribute' => 'voucher.vou_datetime',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->voucher))
                                            return Yii::$app->formatter->asDate($data->voucher->vou_datetime);
                                    }
                                ],
                                [
                                    'attribute' => 'voucher.vou_valid_start',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->voucher))
                                            return Yii::$app->formatter->asDate($data->voucher->vou_valid_start);
                                    }
                                ],
                                [
                                    'attribute' => 'voucher.vou_valid_end',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->voucher))
                                            return Yii::$app->formatter->asDate($data->voucher->vou_valid_end);
                                    }
                                ],
                                'vob_qty',
                                [
                                    'attribute' => 'vob_price',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data->vob_price);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'header' => '',
                                    'format' => 'html',
                                    'options' => ['class' => 'pull-right col-md-1'],
                                    'value' => function($data) {
                                        return Html::a('<i class="fa fa-search"></i>', ['voucher/view', 'id' => $data->vob_id]).'&nbsp;';
                                            // . Html::a('<i class="fa fa-pencil"></i>', ['voucher/update/?id='.$data->vob_id]).'&nbsp;'
                                            // . Html::a('<i class="fa fa-trash-o"></i>', ['voucher/delete/?id='.$data->vob_id], ['class'=>'delete-confirm']);
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
</section>

<?php
$this->registerJs("
    var baseUrl = '".Yii::$app->homeUrl."';
    $('#filtersearch').on('keypress', function(ev) {
        if(ev.which == 13) {
            window.location = baseUrl + 'reward/voucher/index?search=' + $(this).val();
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
");
?>