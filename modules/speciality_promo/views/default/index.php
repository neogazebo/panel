<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ComSpecialityPromoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Speciality Promo';
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
                        <?= Html::a('Back', ['/speciality'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Create Promo', ['create'], ['class' => 'btn btn-success']) ?>
                    </p>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'spt_promo_com_spt_id',
                                'format' => 'html',
                                'value' => function($data){
                                    return $data->speciality->com_spt_merchant_speciality_name;
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
                            'spt_promo_point',
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
                                    return Yii::$app->formatter->asDate($data->spt_promo_start_date);
                                }
                            ],
                            [
                                'label' => 'End',
                                'attribute' => 'spt_promo_end_date',
                                'value' => function($data){
                                    return Yii::$app->formatter->asDate($data->spt_promo_end_date);
                                }
                            ]
                            // 'spt_promo_start_date',
                            // 'spt_promo_end_date',
                            // 'spt_promo_created_date',

                            // ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
