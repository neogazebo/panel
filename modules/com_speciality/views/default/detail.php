<?php

use app\models\CompanySpeciality;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Specialities';
?>
<section class="content-header ">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border"></div><!-- /.box-header -->
                <div class="box-body">
                <p>
                <?= Html::a('<i class="fa fa-chevron-left"></i> Back', ['/speciality'], ['class' => 'btn btn-warning']) ?>
                <?= Html::a('<i class="fa fa-plus-square"></i> Create Speciality', ['#'], [
                        'class' => 'btn btn-success pull-right',
                        'data-toggle' => 'modal', 
                        'data-target' => '#create-speciality',
                        'data-backdrop' => 'static',
                    ]);
                ?>
                <?=
                    $this->render('create', [
                        'model' => new CompanySpeciality()
                    ]);
                ?>
                </p>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'com_spt_type_id',
                            'format' => 'html',
                            'value' => function($data){
                                return $data->type->com_type_name. ' <span class="text-green">('.$data->country->cty_name.')</span>';
                            }
                        ],
                        [
                            'attribute' => 'com_spt_multiple_point',
                            'format' => 'html',
                            'value' => function($data){
                                if (!isset($data->com_spt_multiple_point)) {
                                    return $data->type->com_type_multiple_point;
                                } else {
                                    return $data->com_spt_multiple_point;
                                }
                            }
                        ],
                        [
                            'attribute' => 'com_spt_max_point',
                            'format' => 'html',
                            'value' => function($data){
                                if (!isset($data->com_spt_multiple_point)) {
                                    return $data->type->com_type_max_point;
                                } else {
                                    return $data->com_spt_max_point;
                                }
                            }
                        ],
                        [
                            'label' => 'PIC',
                            'attribute' => 'com_spt_created_by',
                            'value' => function($data){
                                return $data->pic->username;
                            }
                        ],
                        [
                            'label' => 'Create Date',
                            'attribute' => 'com_spt_created_date',
                            'format' => 'html',
                            'value' => function($data){
                                return date('Y-m-d',$data->com_spt_created_date);
                            }
                        ],
                        // [
                        //     'label' => 'Updated Date',
                        //     'attribute' => 'com_spt_updated_date',
                        //     'format' => 'html',
                        //     'value' => function($data){
                        //         return date('Y-m-d',$data->com_spt_updated_date);
                        //     }
                        // ],
                        [
                            'header' => 'Action',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{group} {update} {delete}',
                            'buttons' => [
                                'group' => function($url,$model){
                                    return Html::a('<i class="fa fa-group"></i>',['group','id' => $model->com_spt_id],['data-method' => 'post']);
                                },
                                'update' => function($url,$model){
                                    $html = Html::a('<i class="fa fa-pencil-square-o"></i>',['#'],['class' => 'modalButton', 'data-toggle' => 'modal', 'data-target' => '#edit-speciality-' . $model->com_spt_id, 'data-backdrop' => 'static', 'data-keyboard' => 'false']);
                                    $html .= $this->render('update',[
                                            'model' => $model
                                        ]);
                                    return $html;
                                },
                                'delete' => function($url,$model){
                                    return Html::a('<i class="fa fa-times-circle-o"></i>',['#'],
                                        [
                                            'value' => '/delete?id='.$model->com_spt_id,
                                            'class' => 'gotohell',
                                            'data-title' => 'Delete',
                                            'data-text' => 'Are you sure ?'
                                        ]);
                                },
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
$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') .'common/js/activeformvalidation/beforesubmit.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
?>
