<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = 'Snap & Earn Point';
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Back', ['/logwork'], ['class' => 'btn btn-warning']) ?>  
                    </div>
                    <div class="pull-right">
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?=
                            GridView::widget([
                                'id' => 'snapearn-point',
                                'layout' => '{items} {summary} {pager}',
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                    	'attribute' => 'spo_name',
                                    	'value' => function($data) {
                                			return $data->spo_name;
                                    	}
                                    ],
                                    [
                                    	'attribute' => 'spo_point',
                                    	'value' => function($data) {
                                			return Yii::$app->formatter->asDecimal($data->spo_point, 0);
                                    	}
                                    ],
                                    [
                                        'attribute' => 'spo_updated_date',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return Yii::$app->formatter->asDatetime($data->spo_updated_date, "php:d M Y H:i:s");
                                        }
                                    ],
                                    'userCreated.username',
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '<span class="pull-right actionColumn">{update}</span>',
                                        'buttons' => [
                                            'update' => function($url, $model) {
                                                // return Html::button('<i class="fa fa-pencil-square-o"></i>', ['value' => Url::to(['update?id='.$model->spo_id]), 'class' => 'modalButton']);
                                                // return Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->spo_id]);
                                            },
                                        ]
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

<!-- widget to create render modal -->
<?php
    Modal::begin([
        'header' => '</button><h4 class="modal-title">Point</h4>',
        'id' => 'modal',
        'size' => 'modal-md',
    ]);
?>
<div id="modalContent"></div>
<?php Modal::end(); ?>
