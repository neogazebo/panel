<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ComSpecialityPromoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Com Speciality Promos';
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
                        <?= Html::a('Create Com Speciality Promo', ['create'], ['class' => 'btn btn-success']) ?>
                    </p>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'spt_promo_id',
                            'spt_promo_com_spt_id',
                            'spt_promo_description',
                            'spt_promo_point',
                            'spt_promo_created_by',
                            // 'spt_promo_start_date',
                            // 'spt_promo_end_date',
                            // 'spt_promo_created_date',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
