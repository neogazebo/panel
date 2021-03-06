 <?php

use app\components\helpers\SnapearnPointSpeciality;
use app\models\ComSpecialityPromo;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ComSpecialityPromoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Speciality Promo';
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
                    <?= Html::a('<i class="fa fa-plus-square"></i> Create Promo', ['#'], [
                            'class' => 'btn btn-success pull-right',
                            'data-toggle' => 'modal', 
                            'data-target' => '#create-promo',
                            'data-backdrop' => 'static',
                        ]);
                    ?>
                    <?=
                        $this->render('create',[
                            'model' => new ComSpecialityPromo()
                        ]);
                    ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'spt_promo_com_spt_id',
                                'format' => 'html',
                                'value' => function($data){
                                    return $data->speciality->type->com_type_name.' ('.$data->speciality->country->cty_name.')';
                                }
                            ],
                            [
                                'label' => 'Event Promo',
                                'attribute' => 'spt_promo_description',
                                'format' => 'html',
                                'value' => function($data){
                                    return $data->spt_promo_description;
                                }
                            ],                            
                            [
                                'label' => 'Day Promo',
                                'attribute' => 'spt_promo_day_promo',
                                'format' => 'html',
                                'value' => function($data){
                                    return $data->spt_promo_day_promo;
                                }
                            ],
                            [
                                'label' => 'Point',
                                'attribute' => 'spt_promo_multiple_point',
                                'value' => function($data){
                                    return floatval($data->spt_promo_multiple_point);
                                }
                            ],
                            [
                                'label' => 'PIC',
                                'attribute' => 'spt_promo_created_by',
                                'value' => function($data){
                                    return $data->pic->username;
                                }
                            ],
                            [
                                'label' => 'Start',
                                'attribute' => 'spt_promo_start_date',
                                'value' => function($data){
                                    return date('Y-m-d',$data->spt_promo_start_date);
                                }
                            ],
                            [
                                'label' => 'End',
                                'attribute' => 'spt_promo_end_date',
                                'value' => function($data){
                                    return date('Y-m-d',$data->spt_promo_end_date);
                                }
                            ],
                            [
                                'header' => 'Action',
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                                'buttons' => [
                                    'update' => function($url,$model){
                                        $visible = ($model->spt_promo_end_date < time()) ? 'hide' : '';
                                        $model->spt_promo_start_date = date('Y-m-d',$model->spt_promo_start_date);
                                        $model->spt_promo_end_date = date('Y-m-d',$model->spt_promo_end_date);
                                        $html = Html::a('<i class="fa fa-pencil-square-o"></i>',['#'],['class' => 'modalButton '.$visible, 'data-toggle' => 'modal', 'data-target' => '#edit-promo-' . $model->spt_promo_id, 'data-backdrop' => 'static', 'data-keyboard' => 'false']);
                                        $html .= $this->render('update',[
                                                'model' => $model
                                            ]);
                                        return $html;
                                    },
                                    'delete' => function($url,$model){
                                        $visible = (strtotime($model->spt_promo_start_date) <= time()) ? 'hide' : '';
                                        return Html::a('<i class="fa fa-times-circle-o"></i>',['#'],
                                            [
                                                'value' => '/promo/default/delete?id='.$model->spt_promo_id,
                                                'class' => 'gotohell '.$visible,
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
