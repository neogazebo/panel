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
$this->title = 'Detail Log Work';
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
                                    'label' => 'Description',
                                    'attribute' => 'wrk_description',
                                ],
                                [
                                    'label' => 'Total Point',
                                    'attribute' => 'wrk_point'
                                ],
                                [
                                    'label' => 'Record Activity',
                                    'attribute' => 'wrk_time',
                                    'value' => function($data){
                                        date_default_timezone_set('UTC');
                                        return date('H:i:s',$data->wrk_time);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{detail} </span>',
                                    'buttons' => [
                                        'detail' => function($url,$model) {
                                            return Html::a('<i class="fa fa-search"></i>', ['view', 'id' => $model->wrk_id]);
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