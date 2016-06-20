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
                <div class="form-inline">
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
                        <form role="form" method="post" action="/logwork">
                            <input type="hidden" name="wrk_by" id="wrk_by">
                            <input type="hidden" name="wrk_param_id" id="wrk_param_id">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                        </form>
                    </div>
                </div>
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
                                    'label' => 'Devision',
                                    'attribute' => 'wrk_type',
                                    'value' => function($data){
                                        if ($data->wrk_type = 1) {
                                            return 'Snap & Earn';
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Record Activity',
                                    'attribute' => 'wrk_time',
                                    'value' => function($data){
                                        // if (!empty($data->)) {
                                        //     return date('H:i:s',$data->getTime($data->wrk_by)->total_record);
                                        // }
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