<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts';
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
                                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'layout' => '{items} {summary} {pager}',
                            'filterModel' => $searchModel,
                            'columns' => [
                                // ['class' => 'yii\grid\SerialColumn'],

                                // 'acc_id',
                                // 'acc_facebook_id',
                                // 'acc_facebook_email:email',
                                // 'acc_facebook_graph:ntext',
                                // 'acc_google_id',
                                // 'acc_google_email:email',
                                // 'acc_google_token',
                                'acc_screen_name',
                                'acc_facebook_email:email',
                                'acc_cty_id',
                                // 'acc_photo',
                                // 'acc_created_datetime:datetime',
                                // 'acc_updated_datetime:datetime',
                                'acc_status',
                                // 'acc_tmz_id',
                                // 'acc_birthdate',
                                'acc_address',
                                'acc_gender',

                                ['class' => 'yii\grid\ActionColumn'],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
