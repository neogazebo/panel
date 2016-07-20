<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use yii\helpers\ArrayHelper;

$this->title = 'Reward Reference';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <form class="form-inline" action="reward" method="get">
                        <div class="form-group">
                        <label>Username</label>
                        <div>
                            <input type="text" class="form-control" name="username" >
                        </div>
                        </div>
                        <div class="form-group">
                            <label>MSISDN</label>
                            <div>
                                <input type="text" class="form-control" name="rwd_msisdn">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>CODE</label>
                            <div>
                                <input type="text" class="form-control" name="rwd_code">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="rwd_status" class="form-control" style="width: 100%;">
                                <option value="">All</option>
                                <option value="1">Redeemed</option>
                                <option value="0">Unredeemed</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Transaction Time</label>
                            <div>
                                <input type="text" class="form-control" name="rwd_daterange" id="the_daterange">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                        </div>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'redemption-reference',
                            'options' => [
                                'style' => 'font-size: 13px'
                            ],
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'pjax' => true,
                            'pjaxSettings' => [
                                'neverTimeout' => true,
                            ],
                            'columns' => [
                                [
                                    'label' => 'Username',
                                    'attribute' => 'account.acc_screen_name'
                                ],
                                
                                [
                                    'label' => 'Transaction Time',
                                    'attribute' => 'rdr_datetime',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->rdr_datetime));
                                    }
                                ],
                                'rdr_name',
                                'rdr_msisdn',
                                'rdr_reference_code',
                                'rdr_vod_sn',
                                'rdr_vod_code',
                                [
                                    'label' => 'Point',
                                    'attribute' => 'rdr_vou_value',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data->rdr_vou_value);
                                    }
                                ],
                                [
                                    'attribute' => 'rdr_status',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return $data->rdr_status == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-circle-o"></i>';
                                    }
                                ]
                            ],
                        ]);
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
