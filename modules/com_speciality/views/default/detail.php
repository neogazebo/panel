<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Default Specialities';
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
                    <?= Html::a('Company Speciality', ['create'], ['class' => 'btn btn-success']) ?>
                </p>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'com_spt_merchant_speciality_name',
                            'format' => 'html',
                            'value' => function($data){
                                return $data->com_spt_merchant_speciality_name;
                            }
                        ],
                        [
                            'attribute' => 'com_spt_multiple_point',
                            'format' => 'html',
                            'value' => function($data){
                                return $data->com_spt_multiple_point;
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
                            'label' => 'Start Date',
                            'attribute' => 'com_spt_created_date',
                            'format' => 'html',
                            'value' => function($data){
                                return date('Y-m-d',$data->com_spt_created_date);
                            }
                        ],
                        [
                            'label' => 'End Date',
                            'attribute' => 'com_spt_updated_date',
                            'format' => 'html',
                            'value' => function($data){
                                return date('Y-m-d',$data->com_spt_updated_date);
                            }
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
