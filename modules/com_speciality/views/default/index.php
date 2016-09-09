<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Active Speciality';
$this->params['breadcrumbs'][] = $this->title;
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
                    <?= Html::a('Company Speciality', ['detail'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Promo', ['/promo'], ['class' => 'btn btn-danger']) ?>
                </p>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'com_spt_merchant_speciality_name',
                            'format' => 'html',
                            'value' => function($data){
                                // echo "<pre>";
                                // var_dump($data->promo);exit;
                                $promo = ($data->promo) ? ' <span class="label label-warning">promo</span>' : '';
                                return $data->com_spt_merchant_speciality_name.$promo;
                            }
                        ],
                        [
                            'label' => 'Event Promo',
                            'attribute' => 'com_spt_id',
                            'format' => 'html',
                            'value' => function($data){
                                if ($data->promo) {
                                    return $data->promo->spt_promo_description;
                                }
                            }
                        ], 
                        [
                            'label' => 'Point',
                            'attribute' => 'com_spt_multiple_point',
                            'format' => 'html',
                            'value' => function($data){
                                return ($data->promo) ? $data->promo->spt_promo_point : $data->com_spt_multiple_point;
                            }
                        ],
                        [
                            'label' => 'PIC',
                            'attribute' => 'com_spt_created_by',
                            'value' => function($data){
                                return ($data->promo) ? $data->promo->pic->username : $data->pic->username;
                            }
                        ],
                        [
                            'label' => 'Start Date',
                            'attribute' => 'com_spt_created_date',
                            'format' => 'html',
                            'value' => function($data){
                                if ($data->promo){
                                    return date('Y-m-d',$data->promo->spt_promo_start_date);
                                }
                                
                            }
                        ],
                        [
                            'label' => 'End Date',
                            'attribute' => 'com_spt_updated_date',
                            'format' => 'html',
                            'value' => function($data){
                                if ($data->promo){
                                    return date('Y-m-d',$data->promo->spt_promo_end_date);
                                }
                            }
                        ]
                        //['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
