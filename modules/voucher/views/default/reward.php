<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use yii\helpers\ArrayHelper;
use kartik\export\ExportMenu;

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

                        <div>
                            <div class="form-group">
                                <label>Username</label>
                                <div>
                                    <input type="text" class="form-control" name="username" value="<?= (!empty($_GET['username'])) ? $_GET['username'] : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>MSISDN</label>
                                <div>
                                    <input type="text" class="form-control" name="rwd_msisdn" value="<?= (!empty($_GET['rwd_msisdn'])) ? $_GET['rwd_msisdn'] : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>CODE</label>
                                <div>
                                    <input type="text" class="form-control" name="rwd_code" value="<?= (!empty($_GET['rwd_code'])) ? $_GET['rwd_code'] : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="rwd_status" class="form-control select2" style="width: 100%;">
                                    <option <?= (empty($_GET['rwd_status']) || $_GET['rwd_status'] == '') ? 'selected' : '' ?> value="">All</option>
                                    <option <?= (!empty($_GET['rwd_status']) && $_GET['rwd_status'] === 'close') ? 'selected' : '' ?> value="close">Close</option>
                                    <option <?= (!empty($_GET['rwd_status']) && $_GET['rwd_status'] === 'open') ? 'selected' : '' ?> value="open">Open</option>

                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Transaction Time</label>
                                <div>
                                    <input type="text" class="form-control" name="rwd_daterange" id="the_daterange" value="<?= (!empty($_GET['rwd_daterange'])) ? $_GET['rwd_daterange'] : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <select name="acc_cty_id" class="form-control select2" style="width: 100%;">
                                      <option value="All">All</option>
                                      <option value="ID" <?= (!empty($_GET['acc_cty_id']) && $_GET['acc_cty_id'] == 'ID') ? 'selected' : '' ?>>Indonesia</option>
                                      <option value="MY" <?= (!empty($_GET['acc_cty_id']) && $_GET['acc_cty_id'] == 'MY') ? 'selected' : '' ?>>Malaysia</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="margin-top-10">
                            <div class="form-group">
                                <label>Limit</label>
                                <div>
                                    <select class="form-control select2" name="limit">
                                        <option value="20" <?= (!empty($_GET['limit']) && $_GET['limit'] == '20') ? 'selected' : '' ?>>20</option>
                                        <option value="30" <?= (!empty($_GET['limit']) && $_GET['limit'] == '30') ? 'selected' : '' ?>>30</option>
                                        <option value="50" <?= (!empty($_GET['limit']) && $_GET['limit'] == '50') ? 'selected' : '' ?>>50</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <button name="output_type" value="view" type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                            </div>
                        </div>

                        <div class="margin-top-10">
                            <div class="form-group">
                                <label>Export</label><br>
                                <button name="output_type" value="excel" type="submit" class="btn btn-primary btn-flat"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                            </div>
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
                            'bordered' => false,
                            'striped' => false
                        ]);
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
