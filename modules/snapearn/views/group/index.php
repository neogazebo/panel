<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = 'Snap & Earn Group';
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="pull-right">
                        <?= Html::button('<i class="fa fa-plus-square"></i> New Group', ['value' => Url::to(['create']), 'class' => 'btn btn-primary modalButton']); ?>
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
                                    	'attribute' => 'spg_name',
                                    	'value' => function($data) {
                                			return $data->spg_name;
                                    	}
                                    ],
                                    [
                                        'attribute' => 'spg_created_date',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return Yii::$app->formatter->asDatetime($data->spg_created_date, "php:d M Y");
                                        }
                                    ],
                                    [
                                        'attribute' => 'user.username',
                                        'header' => 'User Created'
                                    ],
                                    [
                                        'attribute' => 'spg_updated_date',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return Yii::$app->formatter->asDatetime($data->spg_updated_date, "php:d M Y");
                                        }
                                    ],
                                    [
                                        'attribute' => 'userUpdate.username',
                                        'header' => 'User Updated'
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '<span class="pull-right actionColumn">{list} {update}</span>',
                                        'buttons' => [
                                            'list' => function($url, $model) {
                                                return Html::a('<i class="fa fa-group"></i>', ['user-list', 'id' => $model->spg_id]);
                                            },
                                            'update' => function($url, $model) {
                                                return Html::button('<i class="fa fa-pencil-square-o"></i>', ['value' => Url::to(['update?id='.$model->spg_id]), 'class' => 'modalButton']);
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
        'header' => '</button><h4 class="modal-title">Snap &amp; Earn Group</h4>',
        'id' => 'modal',
        'size' => 'modal-md',
    ]);
?>
<div id="modalContent"></div>
<?php Modal::end(); ?>
