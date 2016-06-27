<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log Work';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header ">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                <form role="form" class="form-inline" method="post" action="/logwork">
                    <div class="pull-right">
                        <div class="form-group">
                        <label>Username</label>
                        <?= 
                            Typeahead::widget([
                                'name' => 'merchant',
                                'options' => ['placeholder' => 'Find User'],
                                'pluginOptions' => [
                                    'highlight'=>true,
                                    'minLength' => 3
                                ],
                                'pluginEvents' => [
                                    "typeahead:select" => "function(ev, suggestion) { $('#wrk_by').val(suggestion.id); }",
                                ],
                                'dataset' => [
                                    [
                                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('id')",
                                        'display' => 'value',
                                        'remote' => [
                                            'url' => Url::to(['user-list']) . '?q=%QUERY',
                                            'wildcard' => '%QUERY'
                                        ],
                                        'limit' => 20
                                    ]
                                ]
                            ]);
                        ?>
                        </div>
                        <!-- <div class="form-group">
                        <label>Devision</label> -->
                        <?php 
                            // Typeahead::widget([
                            //     'name' => 'merchant',
                            //     'options' => ['placeholder' => 'Devision'],
                            //     'pluginOptions' => [
                            //         'highlight'=>true,
                            //         'minLength' => 3
                            //     ],
                            //     'pluginEvents' => [
                            //         "typeahead:select" => "function(ev, suggestion) { $('#wrk_param_id').val(suggestion.id); }",
                            //     ],
                            //     'dataset' => [
                            //         [
                            //             'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('id')",
                            //             'display' => 'value',
                            //             'remote' => [
                            //                 'url' => Url::to(['devision']) . '?q=%QUERY',
                            //                 'wildcard' => '%QUERY'
                            //             ],
                            //             'limit' => 20
                            //         ]
                            //     ]
                            // ]);
                        ?>
                        <!-- </div> -->
                        <div class="form-group">
                            <label>Date range</label><br>
                            <div class="input-group">
                                <div class="input-group-addon" for="reservation">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="wrk_daterange" class="form-control pull-right" id="the_daterange" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="wrk_by" id="wrk_by">
                            <input type="hidden" name="wrk_param_id" id="wrk_param_id">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                        </div>
                    </div>
                </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'label' => 'Username',
                                    'attribute' => 'wrk_by',
                                    'value' => function($data){
                                        return $data->user->username;
                                    }
                                ],
                                [
                                    'label' => 'Total Point',
                                    'attribute' => 'wrk_point',
                                    'value' => function($data){
                                        return $data->total_point;
                                    }
                                ],
                                [
                                    'label' => 'Total Approved',
                                    'attribute' => 'wrk_type',
                                    'value' => function($data) {
                                        return $data->total_approved;
                                    }
                                ],
                                [
                                    'label' => 'Total Rejected',
                                    'attribute' => 'wrk_type',
                                    'value' => function($data) {
                                        return $data->total_rejected;
                                    }
                                ],
                                [
                                    'label' => 'Rejection Rate',
                                    'attribute' => 'wrk_type',
                                    'value' => function($data){
                                        return Yii::$app->formatter->asPercent($data->rejected_rate);
                                    }
                                ],
                                [
                                    'label' => 'Total Work Time',
                                    'attribute' => 'wrk_time',
                                    'value' => function($data){
                                        date_default_timezone_set('UTC');
                                        return date('H:i:s',$data->total_record);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{detail} </span>',
                                    'buttons' => [
                                        'detail' => function($url,$model) {
                                            return Html::a('<i class="fa fa-search"></i>', ['view', 'id' => $model->wrk_by]);
                                        },
                                    ]
                                ]

                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>