<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Redemption Reference';
$dataProvider->sort->defaultOrder = ['rdr_datetime' => SORT_DESC];
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border"></div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'redemption-reference',
                            'options' => [
                                'style' => 'font-size: 13px'
                            ],
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'pjax' => true,
                            'pjaxSettings' => [
                                'neverTimeout' => true,
                            ],
                            'columns' => [
                                'account.acc_screen_name',
                                [
                                    'attribute' => 'rdr_datetime',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->rdr_datetime));
                                    }
                                ],
                                'rdr_name',
                                'rdr_msisdn',
                                'rdr_reference_code',
                                'rdr_vod_sn',
                                'rdr_vod_code',
                                'rdr_vod_expired',
                                [
                                    'attribute' => 'rdr_vou_value',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data->rdr_vou_value);
                                    }
                                ],
                                [
                                    'attribute' => 'rdr_status',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return $data->rdr_status == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-circle-o"></i>';
                                    }
                                ]
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]);
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
