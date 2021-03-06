<?php

use app\models\CompanyType;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Types';
?>
<section class="content-header ">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::a('<i class="fa fa-chevron-left"></i> Back', ['/speciality'], ['class' => 'btn btn-warning']) ?>
                    <?= Html::a('<i class="fa fa-plus-square"></i> Create Company Type', ['#'], [
                            'class' => 'btn btn-success pull-right',
                            'data-toggle' => 'modal', 
                            'data-target' => '#create-type',
                            'data-backdrop' => 'static',
                        ]);
                    ?>
                    <?=
                        $this->render('create', [
                            'model' => new CompanyType()
                        ]);
                    ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'com_type_name',
                            'value' => function($data){
                                return $data->com_type_name;
                            }
                        ],
                        [
                            'attribute' => 'com_type_multiple_point',
                            'value' => function($data){
                                return floatval($data->com_type_multiple_point);
                            }
                        ],
                        [
                            'attribute' => 'com_type_max_point',
                            'value' => function($data){
                                return $data->com_type_max_point;
                            }
                        ],
                        [
                            'attribute' => 'com_type_created_by',
                            'value' => function($data){
                                return $data->pic->username;
                            }
                        ],
                        // 'com_type_created_date',
                        // 'com_type_updated_date',
                        // 'com_type_deleted_date',
                        [   
                            'header' => 'Action',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update} <!-- {delete}-->',
                            'buttons' => [
                                'update' => function($url,$model){
                                    $html = Html::a('<i class="fa fa-pencil-square-o"></i>',['#'],
                                        [
                                            'class' => 'modalButton', 
                                            'data-toggle' => 'modal', 
                                            'data-target' => '#edit-type-' . $model->com_type_id, 
                                            'data-backdrop' => 'static', 
                                            'data-keyboard' => 'false'
                                        ]);
                                    $html .= $this->render('update',[
                                            'model' => $model
                                        ]);
                                    return $html;
                                },
                            // 'delete' => function($url,$model){
                            //         return Html::a('<i class="fa fa-times-circle-o"></i>',['#'],
                            //         [
                            //             'value' => '/type/default/delete?id='.$model->com_type_id,
                            //             'class' => 'gotohell',
                            //             'data-title' => 'Delete',
                            //             'data-text' => 'Are you sure ?'
                            //         ]);
                            //     },
                            ]
                        ],
                    ],
                    'tableOptions' => ['class' => 'table table-striped table-hover']
                ]); ?>
               </div>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerJsFile(Yii::$app->homeUrl .'common/js/activeformvalidation/beforesubmit.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
?>
