<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

$this->title = 'Merchant Signup List';
$search = !empty(Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    &nbsp;
                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" id="filtersearch" value="<?= $search ?>" class="form-control input-sm" placeholder="Search Merchant Signup">
                            <span class="fa fa-search form-control-feedback"></span>
                        </div>
                    </div>
                </div> <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'merchant-signup',
                            'dataProvider' => $dataProvider,
                            'layout' => '{items} {summary} {pager}',
                            'columns' => [
                                'mer_bussines_name',
                                'mer_company_name',
                                'mer_login_email',
                                [
                                    'attribute' => 'created_date',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDate($data->created_date);
                                    }
                                ],
                                [
                                    'attribute' => 'updated_date',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDate($data->updated_date);
                                    }
                                ],
                                [
                                    'attribute' => 'mer_reviewed',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return $data->mer_reviewed != 0 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-circle-o"></i>';
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right">{view} {review} {xreview}</span>',
                                    'buttons' => [
                                        'view' => function($url, $model) {
                                            return Html::a('
                                                <i class="fa fa-caret-square-o-down"></i> View
                                            ', [
                                                '/merchant-signup/default/view/?id=' . $model->id
                                            ], ['class' => 'btn btn-info btn-xs']);
                                        },
                                        'review' => function($url, $model) {
                                            if($model->mer_reviewed == 0)
                                            return Html::a('
                                                <i class="fa fa-caret-square-o-down"></i> Review
                                            ', [
                                                '/merchant-signup/default/review/?id=' . $model->id
                                            ], ['class' => 'btn btn-warning btn-xs']);
                                        },
                                        /* 'xreview' => function($url, $model) {
                                            if($model->mer_reviewed == 0)
                                                return Html::a('<i class="fa fa-pencil"></i> Edit', ['/merchant-signup/default/xreview/?id=' . $model->id], ['class' => 'btn btn-success btn-xs']);
                                        } */
                                    ]
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]);
                        ?>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>

<?php
$this->registerJs("
    $('#filtersearch').popover();
    $('#filtersearch').on('keypress', function(ev) {
        if(ev.which == 13) {
            window.location = baseUrl + 'merchant-signup?search=' + encodeURIComponent($(this).val());
        }
    });
", yii\web\View::POS_END, 'merchant-signup');
?>
