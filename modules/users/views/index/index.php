<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\modal;
use yii\helpers\Url;

$this->title = 'List Role';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::a('<i class="fa fa-plus-square"></i> Create User', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'ListRole',
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'username',
                                'email',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{view} {update} {delete}</span>',
                                    'buttons' => [
                                        'view' => function($url, $model) {
                                            return Html::a('<i class="fa fa-search"></i>', ['detail?id=' . $model->id]);
                                        },
                                        'update' => function($url,$model) {
                                            return Html::button('<i class="fa fa-pencil-square-o"></i>', ['value' => Url::to(['update?id='.$model->id]), 'class' => 'modalButton']);
                                        },
                                        'delete' => function($url,$model) {
                                            return Html::button('<i class="fa fa-times-circle-o"></i>', ['value' => Url::to(['delete?id=' . $model->id]), 'class' => 'deleteBtn']);
                                        }
                                    ],
                                ],
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>