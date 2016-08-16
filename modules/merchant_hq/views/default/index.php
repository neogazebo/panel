<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = 'Merchant HQ Management Dashboard';

$this->registerCssFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/js/plugins/waitme/waitMe.css');

$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/js/plugins/waitme/waitMe.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);

$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'pages/MerchantHqManager.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);

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
                        <?php //echo Html::button('<i class="fa fa-plus-square"></i> New Group', ['value' => Url::to(['create']), 'class' => 'btn btn-primary modalButton']); ?>
                        <button type="button" class="btn btn-primary modalButton" data-toggle="modal" data-target="#add-hq-modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus-square"></i>&nbsp;&nbsp; Add New HQ</button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?=
                            GridView::widget([
                                'id' => 'merchant-hq',
                                'layout' => '{items} {summary} {pager}',
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                        'label' => 'HQ Name',
                                        'attribute' => 'com_name',
                                    ],
                                    [
                                        'attribute' => 'com_created_date',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return Yii::$app->formatter->asDatetime($data->spg_created_date, "php:d M Y");
                                        }
                                    ],
                                    'com_subcategory_id',
                                    [
                                        'header' => 'Actions',
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

<?= $this->render('/partials/modal', ['categories' => $categories]) ?>
