<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Snap & Earn List';
?>
<section class="content-header ">
    <h1><?= $this->title?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">&nbsp;</div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'ListRole',
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'header' => 'Receipt',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return Html::img($data->image, ['height' => 32]);
                                    }
                                ],
                                'merchant.com_name',
                                'category.cat_name',
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
                                'adminApproved.username',
                                [
                                    'attribute' => 'sna_approved_datetime',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime($data->sna_approved_datetime);
                                    }
                                ],
                                'adminRejected.username',
                                [
                                    'attribute' => 'sna_rejected_datetime',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime($data->sna_rejected_datetime);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{update} {delete}</span>',
                                    'buttons' => [
                                        'update' => function($url,$model) {
                                            return Html::a('<i class="fa fa-pencil-square-o"></i>', ['to-update', 'id' => $model->sna_id]);
                                        },
                                        'delete' => function($url,$model) {
                                            return Html::a('<i class="fa fa-times-circle-o"></i>', ['delete', 'id' => $model->sna_id]);
                                        }
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

?>