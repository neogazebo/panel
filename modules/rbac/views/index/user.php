<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

$this->title = 'List User "' . $name . '" Role';
$dataProvider->sort->attributes['user.username'] = [
    'asc' => ['user.username' => SORT_ASC],
    'desc' => ['user.username' => SORT_DESC],
];
?>
<section class="content-header ">
    <h1><?= $this->title?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::a('<i class="fa fa-chevron-left"></i> Back', ['cancel'], ['class' => 'btn btn-flat btn-success btn-sm']) ?>
                    <div class="pull-right">
                        <?= Html::button('<i class="fa fa-plus-square"></i> Assign User', ['type' => 'button','value' => Url::to(['assign?role=' . urlencode($name)]), 'class' => 'modalButton btn btn-flat btn-warning btn-sm']); ?> 
                        <?= Html::a('<i class="fa fa-plus-square"></i> Multi Assign', ['multi-assign?role='.urlencode($name)], ['class' => 'btn btn-flat btn-success btn-sm']) ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'list-user-in-role',
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'item_name',
                                'user.username',
                                [
                                    'label' => 'Email',
                                    'attribute' => 'item_name',
                                    'value' => 'user.email'
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime($data->created_at);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{revoke}</span>',
                                    'buttons' => [
                                        'revoke' => function($url, $model) {
                                            return Html::button('<i class="fa fa-times-circle-o"></i>', 
                                                ['value' => 'revoke?role='.$model->item_name.'&&userId='.$model->user->id.'&&name='.$model->user->username,'class' => 'deleteBtn'
                                                ]);
                                        }
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
        'header' => '</button><h4 class="modal-title"></h4>',
        'id' => 'modal',
        'size' => 'modal-md',
    ]);
?>
<div id="modalContent"></div>
<?php Modal::end(); ?>

