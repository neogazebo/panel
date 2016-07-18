<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

$this->title = $model->acc_screen_name;
$model->acc_gender = ($model->acc_gender == 1) ? 'Male' : 'Female';

$this->registerCss("
    #gmap-waypoints {
        position: relative;
        display: block;
        width: 100%;
    }
    .margin {
        margin: 10px;
        position: relative;
        width: 200px;
    }
    .profile-user-img.img-responsive.img-circle {
        max-height: 100px;
        max-width: 100px;
        overflow: hidden;
    }
    .timeline-inverse > li > .timeline-item {
        background: transparent;
        border: 0px solid #fff;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .nav-tabs-custom > .nav-tabs {
        margin: 0;
        border-bottom-color: #DDDDDD;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
        background-color: #eee;
    }
    .nav-tabs-custom > .tab-content {
        background: #eee;
        padding: 10px;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    }
");
?>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="<?= (!empty($model->acc_photo)) ? Yii::$app->params['memberUrl'].$model->acc_photo : $this->theme->baseUrl.'/dist/img/manis.png'?>" alt="<?= $model->acc_screen_name ?>">
                    <h3 class="profile-username text-center"><?= $model->acc_screen_name ?></h3>
                    <p class="text-muted text-center">Age <?= (!empty($model->acc_birthdate)) ? date('Y') - date('Y', $model->acc_birthdate) .' , ' : ' ' ?> <?= $model->acc_gender  ?></p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Currency </b> <a class="pull-right"><?= !empty($model->country) ? $model->country->cty_currency_name_iso3 : '' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Country </b> <a class="pull-right"><?= ($model->acc_cty_id == 'MY') ? 'Malaysia' : 'Indonesia' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Current Point </b> <a class="pull-right"><?= (!empty($model->lastPointMember())) ? Yii::$app->formatter->asDecimal($model->lastPointMember()) : '<a class="pull-right"><span class="not-set">(not set)</span></a>' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Last Activity </b> <a class="pull-right"><?= (!empty($model->lastLogin())) ? Yii::$app->formatter->asDate($model->lastLogin()->adv_last_access) : '<a class="pull-right"><span class="not-set">(not set)</span></a>' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Facebook Id </b> <a href="https://www.facebook.com/<?= $model->acc_facebook_id ?>" target="blank_" class="pull-right"> <?= (!empty($model->acc_facebook_id)) ? $model->acc_facebook_id : '<a class="pull-right"><span class="not-set">(not set)</span></a>' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Registered Since  </b> <a class="pull-right"><?= (!empty($model->acc_created_datetime)) ? Yii::$app->formatter->asDate($model->acc_created_datetime) : '<a class="pull-right"><span class="not-set">(not set)</span></a>' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Device Active  </b> <a class="pull-right"><?= (!empty($model->activeDevice())) ? $model->activeDevice()->dvc_model : '<a class="pull-right"><span class="not-set">(not set)</span></a>' ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>OS Version  </b> <a class="pull-right"><?= (!empty($model->activeDevice())) ? $model->activeDevice()->dvc_os_version : '<a class="pull-right"><span class="not-set">(not set)</span></a>'?></a>
                        </li>
                    </ul>
                    <?= Html::button('<i class="fa fa-dollar"></i> Point Correction', ['value' => Url::to(['correction?id=' . $model->acc_id]), 'class' => 'btn btn-primary modalButton']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="nav-tabs-custom box-primary">
                <ul class="nav nav-tabs">
                    <li class=""><a aria-expanded="false" href="#activity" data-toggle="tab">Activity</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="activity">
                        <!-- The timeline -->
                        <ul class="timeline timeline-inverse">
                            <!-- timeline time label -->
                            <?php foreach ($model->lastLocation() as $location): ?>
                            <li class="time-label">
                                <span class="bg-red">
                                    Timeline
                                </span>
                            </li>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <li>
                                <i class="fa fa-map-marker bg-blue"></i>
                                <div class="timeline-item">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Last Location</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div style="display: block;" class="box-body">
                                            <?=
                                            \app\components\widgets\GmapLocation::widget([
                                                'lat' => $location->adv_last_latitude,
                                                'long' => $location->adv_last_longitude,
                                                'height' => 150,
                                                'type' => 'static'
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                            <li>
                                <i class="fa fa-pie-chart bg-purple"></i>
                                <div class="timeline-item">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Top Categories</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <select name="filter_chart" id="filter_chart" class="form-control select2" style="display: none;">
                                                        <option value="thisMonth">This Month </option>
                                                        <option value="lastMonth">Last Month </option>
                                                        <option value="thisWeek">This Week </option>
                                                        <option value="lastWeek">Last Week </option>
                                                    </select>
                                                    <span id="testing" class="input-group-addon">.00</span>
                                                </div>
                                                <div class="clearfix"></div>
                                                <p class="chart-notes"></p>
                                            </div>
                                            <div class="col-sm-7">
                                                <canvas id="pieChart" data-url="top-chart" data-key="<?= $model->acc_id ?>" style="height: 250px" value="wow"></canvas>
                                            </div>
                                        </div>
                                        <div class="overlay">
                                            <div id="fountainG">
                                                <div id="fountainG_1" class="fountainG"></div>
                                                <div id="fountainG_2" class="fountainG"></div>
                                                <div id="fountainG_3" class="fountainG"></div>
                                                <div id="fountainG_4" class="fountainG"></div>
                                                <div id="fountainG_5" class="fountainG"></div>
                                                <div id="fountainG_6" class="fountainG"></div>
                                                <div id="fountainG_7" class="fountainG"></div>
                                                <div id="fountainG_8" class="fountainG"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <i class="fa fa-barcode bg-aqua"></i>
                                <div class="timeline-item">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Snap &amp; Earn History</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <?=
                                            GridView::widget([
                                                'id' => 'list_snapearn',
                                                'options' => [
                                                    'style' => 'font-size: 13px',
                                                ],
                                                'layout' => '{items} {summary} {pager}',
                                                'dataProvider' => $receiptProvider,
                                                'pjax' => true,
                                                'pjaxSettings' => [
                                                    'neverTimeout' => true,
                                                ],
                                                'columns' => [
                                                    [
                                                        'label' => 'Merchant',
                                                        'attribute' => 'sna_com_id',
                                                        'format' => 'html',
                                                        'value' => function($data) {
                                                            if (!empty($data->merchant)) {
                                                                return $data->merchant->com_name . ($data->merchant->com_joined == 1 ? ' <i class="fa fa-check"></i>' : '');
                                                            }
                                                        }
                                                    ],
                                                    [
                                                        'attribute' => 'sna_upload_date',
                                                        'format' => 'html',
                                                        'value' => function($data) {
                                                            return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->sna_upload_date));
                                                        }
                                                    ],
                                                    [
                                                        'attribute' => 'sna_receipt_amount',
                                                        'format' => 'html',
                                                        'value' => function($data) {
                                                            return Yii::$app->formatter->asDecimal($data->sna_receipt_amount);
                                                        }
                                                    ],
                                                    [
                                                        'attribute' => 'sna_point',
                                                        'format' => 'html',
                                                        'value' => function($data) {
                                                            return Yii::$app->formatter->asDecimal($data->sna_point);
                                                        }
                                                    ]
                                                ],
                                                'tableOptions' => ['class' => 'table table-hover']
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </li>
                        <li>
                            <i class="fa fa-line-chart bg-yellow"></i>
                            <div class="timeline-item">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Redemption History</h3>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <h4>History Offers</h4>
                                        <?=
                                        GridView::widget([
                                            'id' => 'history_offer',
                                            'options' => [
                                                'style' => 'font-size: 13px'
                                            ],
                                            'layout' => '{items} {summary} {pager}',
                                            'dataProvider' => $historyOfferProvider,
                                            'pjax' => true,
                                            'pjaxSettings' => [
                                                'neverTimeout' => true,
                                            ],
                                            'columns' => [
                                                'offer.del_title',
                                                [
                                                    'attribute' => 'des_redeem_datetime',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->des_redeem_datetime));
                                                    }
                                                ],
                                                'des_sn'
                                            ],
                                            'tableOptions' => ['class' => 'table table-bordered table-hover']
                                        ]);
                                        ?>
                                        <br />
                                        <h4>History Cash Vouchers</h4>
                                        <?=
                                        GridView::widget([
                                            'id' => 'history_cash',
                                            'options' => [
                                                'style' => 'font-size: 13px'
                                            ],
                                            'layout' => '{items} {summary} {pager}',
                                            'dataProvider' => $historyCashProvider,
                                            'pjax' => true,
                                            'pjaxSettings' => [
                                                'neverTimeout' => true,
                                            ],
                                            'columns' => [
                                                'cvr_pvo_name',
                                                'cvr_com_name',
                                                'cvr_pvd_code',
                                                'cvr_pvd_sn',
                                                [
                                                    'attribute' => 'cvr_pvd_update_datetime',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->cvr_pvd_update_datetime));
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'cvr_pvd_expired',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->cvr_pvd_expired));
                                                    }
                                                ],
                                            ],
                                            'tableOptions' => ['class' => 'table table-bordered table-hover']
                                        ]);
                                        ?>
                                        <br />
                                        <h4>Redemption Reference</h4>
                                        <?=
                                        GridView::widget([
                                            'id' => 'redemption_reference',
                                            'options' => [
                                                'style' => 'font-size: 13px'
                                            ],
                                            'layout' => '{items} {summary} {pager}',
                                            'dataProvider' => $referenceProvider,
                                            'pjax' => true,
                                            'pjaxSettings' => [
                                                'neverTimeout' => true,
                                            ],
                                            'columns' => [
                                                'rdr_name',
                                                [
                                                    'attribute' => 'rdr_vou_value',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDecimal($data->rdr_vou_value);
                                                    }
                                                ],
                                                'rdr_vod_sn',
                                                'rdr_vod_code',
                                                'rdr_vod_expired',
                                                'rdr_vor_trx_id',
                                                'rdr_reference_code',
                                                'rdr_msisdn',
                                                [
                                                    'attribute' => 'rdr_datetime',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->rdr_datetime));
                                                    }
                                                ],
                                            ],
                                            'tableOptions' => ['class' => 'table table-bordered table-hover']
                                        ]);
                                        ?>
                                        <?php
                                        // echo GridView::widget([
                                        //     'id' => 'list_redemption',
                                        //     'options' => [
                                        //         'style' => 'font-size: 13px',
                                        //     ],
                                        //     'layout' => '{items} {summary} {pager}',
                                        //     'dataProvider' => $redeemProvider,
                                        // 'pjax' => true,
                                        // 'pjaxSettings' => [
                                        //     'neverTimeout' => true,
                                        // ],
                                        //     'columns' => [
                                        //         [
                                        //             'header' => 'From',
                                        //             'format' => 'html',
                                        //             'value' => function($data) {
                                        //                 switch ($data->lph_lpe_id) {
                                        //                     case 2:
                                        //                         if (!empty($data->offer))
                                        //                             return $data->offer->del_title;
                                        //                         break;
                                        //                     case 3:
                                        //                         if (!empty($data->voucher))
                                        //                             return $data->voucher->vou_reward_name;
                                        //                         break;
                                        //                     case 4:
                                        //                         if (!empty($data->event))
                                        //                             return $data->event->evt_name;
                                        //                         break;
                                        //                     case 22:
                                        //                         if (!empty($data->user))
                                        //                             return $data->user->acc_screen_name;
                                        //                     case 55:
                                        //                         if (!empty($data->redeem->voucher))
                                        //                             return $data->redeem->voucher->vou_reward_name;
                                        //                 }
                                        //             }
                                        //         ],
                                        //         [
                                        //             'attribute' => 'lph_datetime',
                                        //             'format' => 'html',
                                        //             'value' => function($data) {
                                        //                 return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->lph_datetime));
                                        //             }
                                        //         ],
                                        //         [
                                        //             'attribute' => 'lph_amount',
                                        //             'format' => 'html',
                                        //             'value' => function($data) {
                                        //                 return Yii::$app->formatter->asDecimal($data->lph_amount);
                                        //             }
                                        //         ],
                                        //         [
                                        //             'attribute' => 'lph_total_point',
                                        //             'format' => 'html',
                                        //             'value' => function($data) {
                                        //                 return Yii::$app->formatter->asDecimal($data->lph_total_point);
                                        //             }
                                        //         ],
                                        //         [
                                        //             'attribute' => 'lph_expired',
                                        //             'format' => 'html',
                                        //             'value' => function($data) {
                                        //                 return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->lph_expired));
                                        //             }
                                        //         ],
                                        //     ],
                                        //     'tableOptions' => ['class' => 'table table-hover']
                                        // ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- END timeline item -->
                        <li>
                            <i class="fa fa-line-chart bg-blue"></i>
                            <div class="timeline-item">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Saved Offers</h3>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <?=
                                        GridView::widget([
                                            'id' => 'list_offers',
                                            'options' => [
                                                'style' => 'font-size: 13px',
                                            ],
                                            'layout' => '{items} {summary} {pager}',
                                            'dataProvider' => $offerProvider,
                                            'pjax' => true,
                                            'pjaxSettings' => [
                                                'neverTimeout' => true,
                                            ],
                                            'columns' => [
                                                [
                                                    'label' => 'Promotion',
                                                    'attribute' => 'svo_del_id',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return (!empty($data->promotion) ? $data->promotion->del_title : '');
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'svo_datetime',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->svo_datetime));
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'promotion.del_start',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->promotion))
                                                            return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->promotion->del_start));
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'promotion.del_end',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->promotion))
                                                            return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->promotion->del_end));
                                                    }
                                                ],
                                            ],
                                            'tableOptions' => ['class' => 'table table-hover']
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- END timeline item -->
                        <li>
                            <i class="fa fa-line-chart bg-indigo"></i>
                            <div class="timeline-item">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Saved Rewards</h3>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <?=
                                        GridView::widget([
                                            'id' => 'list_rewards',
                                            'options' => [
                                                'style' => 'font-size: 13px',
                                            ],
                                            'layout' => '{items} {summary} {pager}',
                                            'dataProvider' => $rewardProvider,
                                            'pjax' => true,
                                            'pjaxSettings' => [
                                                'neverTimeout' => true,
                                            ],
                                            'columns' => [
                                                [
                                                    'label' => 'Reward',
                                                    'attribute' => 'svr_pvo_id',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return (!empty($data->posVoucher) ? $data->posVoucher->pvo_name : '');
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'svr_datetime',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->svr_datetime));
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'posVoucher.pvo_valid_start',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->posVoucher))
                                                            return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->posVoucher->pvo_valid_start));
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'posVoucher.pvo_valid_end',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->posVoucher))
                                                            return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->posVoucher->pvo_valid_end));
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'posVoucher.pvo_amount',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->posVoucher))
                                                            return Yii::$app->formatter->asDecimal($data->posVoucher->pvo_amount);
                                                    }
                                                ],
                                            ],
                                            'tableOptions' => ['class' => 'table table-hover']
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- END timeline item -->
                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>
                </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
        </div><!-- /.nav-tabs-custom -->
    </div>
</div>
</section>

<!-- widget to create render modal -->
<?php
    Modal::begin([
        'header' => '</button><h4 class="modal-title">Correction Point</h4>',
        'id' => 'modal',
        'size' => 'modal-md',
    ]);
?>
<div id="modalContent"></div>
<?php Modal::end(); ?>
