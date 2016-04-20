<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Mobile Pulsa';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?php echo Html::a('<i class="fa fa-retweet"></i>  <span>' . Yii::t('app', 'Syncronize Mobile Pulsa Server') . '</span>', ['sync'], ['class' => 'btn btn-primary']) ?>  
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?=
                            GridView::widget([
                                'id' => 'mp-product',
                                'layout' => '{items}{summary}{pager}',
                                'dataProvider' => $dataProviderProduct,
                                'columns' => [
                                    [
                                        'attribute' => 'mpp_product_code',
                                        'label' => 'Product',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return $data->mpp_product_code;
                                        }
                                    ],
                                    [
                                        'attribute' => 'mpp_operator',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return $data->mpp_operator;
                                        }
                                    ],
                                    [
                                        'attribute' => 'mpp_nominal',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return $data->mpp_nominal;
                                        }
                                    ],
                                    [
                                        'attribute' => 'mpp_price',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return $data->mpp_price;
                                        }
                                    ],
                                    [
                                        'attribute' => 'mpp_active_periode',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return $data->mpp_active_periode . ' days';
                                        }
                                    ],
                                    [
                                        'attribute' => 'mpp_last_updated',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return Yii::$app->formatter->asDatetime($data->mpp_last_updated, "php:d M Y H:i:s");
                                        }
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
    var baseUrl = '" . Yii::$app->homeUrl . "';
    $('#filtersearch').on('keypress', function(ev) {
        if(ev.which == 13) {
            window.location = baseUrl + 'mobilepulsa/index/index/?search=' + $(this).val();
        }
    });
", yii\web\View::POS_END, 'epay-buy');
?>
