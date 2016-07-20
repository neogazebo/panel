<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Redemption Reference';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border"></div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'redemption-reference',
                            'options' => [
                                'style' => 'font-size: 13px'
                            ],
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'pjaxSettings' => [
                                'neverTimeout' => true,
                            ],
                            'columns' => [
                                'account.acc_screen_name',
                                [
                                    'attribute' => 'rdr_datetime',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->rdr_datetime));
                                    }
                                ],
                                'rdr_sn'
                            ],
                            'tableOptions' => ['class' => 'table table-bordered table-hover']
                        ]);
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
