<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\components\helpers\Utc;

$this->title = 'Merchant HQ List';

$this->registerCssFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/js/plugins/waitme/waitMe.css');
$this->registerCssFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/css/tablesorter-custom.css');
$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/js/plugins/waitme/waitMe.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/js/plugins/tablesorter/dist/js/jquery.tablesorter.min.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
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
                        <button type="button" class="btn btn-primary modalButton" data-toggle="modal" data-target="#add-hq-modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus-square"></i>&nbsp;Add New HQ</button>
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
                                        'header' => 'HQ Name',
                                        'attribute' => 'com_name',
                                    ],
                                    [
                                        'header' => 'Created At',
                                        'attribute' => 'com_created_date',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return Yii::$app->formatter->asDatetime(Utc::convert($data->com_created_date), "php:d M Y H:i:s");
                                        }
                                    ],
                                    [
                                        'header' => 'Category',
                                        'attribute' => 'com_subcategory_id',
                                        'format' => 'html',
                                        'value' => function($data) {
                                            return $data->category->com_category;
                                        }
                                    ],
                                    [
                                        'header' => 'Actions',
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '<span class="actionColumn">{list} {update} {delete}</span>',
                                        'buttons' => [
                                            'list' => function($url, $model) {
                                                return Html::a('<i class="fa fa-group"></i>', ['/system/merchant-hq/list', 'id' => $model->com_id]);
                                            },
                                            'update' => function($url, $model) use ($categories) {
                                                $output = Html::button('<i class="fa fa-pencil-square-o"></i>', ['class' => 'modalButton', 'data-toggle' => 'modal', 'data-target' => '#edit-hq-modal-' . $model->com_id, 'data-backdrop' => 'static', 'data-keyboard' => 'false']);
                                                $output .= $this->render('/merchant-hq/partials/modal-edit', ['categories' => $categories, 'id' => $model->com_id, 'com_name' => $model->com_name, 'com_subcategory_id' => $model->com_subcategory_id]);
                                                return $output;
                                            },
                                            'delete' => function($url, $model) {
                                                return Html::a('<i class="fa fa-times"></i>', '#', ['data-id' => $model->com_id, 'class' => 'delete']);
                                            },
                                        ],
                                    ]
                                ],
                                'tableOptions' => ['class' => 'table table-striped table-hover'],
                            ]);
                            ?>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->render('/merchant-hq/partials/modal-save', ['categories' => $categories]) ?>
