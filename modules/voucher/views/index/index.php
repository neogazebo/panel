<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Rewards';
$dataProvider->sort->defaultOrder = ['vou_datetime' => SORT_DESC];
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::a('<i class="fa fa-plus-square"></i> New Buy', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'voucher',
                            'layout' => '{items}{summary}{pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'header' => '&nbsp;',
                                    'format' => 'raw',
                                    'value' => function($data) {
                                        return Html::img($data->image, ['height' => 32]);
                                    }
                                ],
                                'vou_reward_name',
                                'business.com_name',
                                [
                                    'attribute' => 'vou_datetime',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->vou_datetime))
                                            return Yii::$app->formatter->asDate($data->vou_datetime);
                                    }
                                ],
                                [
                                    'attribute' => 'vou_valid_start',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->vou_valid_start))
                                            return Yii::$app->formatter->asDate($data->vou_valid_start);
                                    }
                                ],
                                [
                                    'attribute' => 'vou_valid_end',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->vou_valid_end))
                                            return Yii::$app->formatter->asDate($data->vou_valid_end);
                                    }
                                ],
                                'vou_stock_left',
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'header' => '',
                                    'format' => 'raw',
                                    'options' => ['class' => 'pull-right col-md-1'],
                                    'value' => function($data) {
                                        return Html::a('<i class="fa fa-search"></i>', ['index/view-bought/?id='.$data->vou_id]).'&nbsp;'
                                            . Html::a('<i class="fa fa-pencil"></i>', ['index/update/?id='.$data->vou_id]).'&nbsp'
                                            . Html::a('<i class="fa fa-trash-o"></i>', ['index/delete/?id='.$data->vou_id], [
                                                'title' => 'Delete ' . $data->vou_reward_name,
                                                'data' => [
                                                    'confirm' => 'Are you sure want to delete this?',
                                                    'method' => 'post',
                                                    'pjax' => 0
                                                ],
                                            ]);
                                    },
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
    var baseUrl = '".Yii::$app->homeUrl."';
    $('#filtersearch').on('keypress', function(ev) {
        if(ev.which == 13) {
            window.location = baseUrl + 'reward/index/?search=' + $(this).val();
        }
    });
", yii\web\View::POS_READY, 'reward-index');
?>
