<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Snap & Earn List';
$search = !empty(Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
?>
<section class="content-header ">
    <h1><?= $this->title?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <!-- &nbsp;
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" id="filtersearch" value="<?= $search ?>" class="form-control input-sm" placeholder="Search Snap">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div> -->
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'list_snapearn',
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'label' => 'Receipt',
                                    'attribute' => 'sna_receipt_image',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return Html::img($data->image, ['style' => 'max-width: 70px; height: 32px']);
                                    }
                                ],
                                [
                                    'label' => 'Merchant',
                                    'attribute' => 'sna_com_id',
                                    'value' => function($data) {
                                        return $data->merchant['com_name'];
                                    }
                                ],
                                [
                                    'label' => 'Member',
                                    'attribute' => 'sna_acc_id',
                                    'value' => function($data) {
                                        return $data->member->acc_screen_name;
                                    }
                                ],
                                'sna_receipt_number',
                                'sna_receipt_date',
                                [
                                    'attribute' => 'sna_receipt_amount',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data->sna_receipt_amount);
                                    }
                                ],
                                [
                                    'attribute' => 'sna_point',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data->sna_point);
                                    }
                                ],
                                [
                                    'attribute' => 'sna_upload_date',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime($data->sna_upload_date);
                                    }
                                ],
                                [
                                    'label' => 'Date Review',
                                    'attribute' => 'sna_approved_datetime',
                                    'value' => function($data) {
                                        if (!empty($data->sna_approved_datetime)) {
                                            return Yii::$app->formatter->asDateTime($data->sna_approved_datetime);
                                        } elseif (!empty($data->sna_rejected_datetime)) {
                                            return Yii::$app->formatter->asDateTime($data->sna_rejected_datetime);
                                        }
                                        
                                    }
                                ],
                                [
                                    'label' => 'Operator',
                                    'attribute' => 'sna_status',
                                    'value' => function($data) {
                                        if (!empty($data->adminRejected['username'])) {
                                            return $data->adminRejected['username'];
                                        } elseif (!empty($data->adminApproved['username'])) {
                                            return $data->adminApproved['username'];
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Status',
                                    'attribute' => 'sna_status',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if ($data->sna_status == 1) {
                                            return "<i class='fa fa-check approved-status'></i>";
                                        } elseif ($data->sna_status == 2) {
                                            return "<i class='fa fa-close rejected-status'></i>";
                                        }
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{update}</span>',
                                    'buttons' => [
                                        'update' => function($url,$model) {
                                            return Html::a('<i class="fa fa-pencil-square-o"></i>', ['to-update', 'id' => $model->sna_id]);
                                        },
                                    ],
                                ],
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

<?php
$this->registerJs("
    // $('#filtersearch').popover();
    // $('#filtersearch').on('keypress', function(ev) {
    //     if(ev.which == 13) {
    //         window.location = baseUrl + 'snapearn/default?search=' + encodeURIComponent($(this).val());
    //     }
    // });
", yii\web\View::POS_END, 'snapearn-list');
?>
