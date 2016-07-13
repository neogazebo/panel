<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */
// var_dump($model->lastLogin());exit;
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
    #fountainG {
    	position: relative;
    	width: 234px;
    	height: 28px;
    	margin: auto;
        top:  50%;
    }
    .fountainG {
    	position: absolute;
    	top: 0;
    	background-color: rgb(0,0,0);
    	width: 28px;
    	height: 28px;
    	animation-name: bounce_fountainG;
    		-o-animation-name: bounce_fountainG;
    		-ms-animation-name: bounce_fountainG;
    		-webkit-animation-name: bounce_fountainG;
    		-moz-animation-name: bounce_fountainG;
    	animation-duration: 1.5s;
    		-o-animation-duration: 1.5s;
    		-ms-animation-duration: 1.5s;
    		-webkit-animation-duration: 1.5s;
    		-moz-animation-duration: 1.5s;
    	animation-iteration-count: infinite;
    		-o-animation-iteration-count: infinite;
    		-ms-animation-iteration-count: infinite;
    		-webkit-animation-iteration-count: infinite;
    		-moz-animation-iteration-count: infinite;
    	animation-direction: normal;
    		-o-animation-direction: normal;
    		-ms-animation-direction: normal;
    		-webkit-animation-direction: normal;
    		-moz-animation-direction: normal;
    	transform: scale(.3);
    		-o-transform: scale(.3);
    		-ms-transform: scale(.3);
    		-webkit-transform: scale(.3);
    		-moz-transform: scale(.3);
    	border-radius: 19px;
    		-o-border-radius: 19px;
    		-ms-border-radius: 19px;
    		-webkit-border-radius: 19px;
    		-moz-border-radius: 19px;
    }
    #fountainG_1 {
    	left: 0;
    	animation-delay: 0.6s;
    		-o-animation-delay: 0.6s;
    		-ms-animation-delay: 0.6s;
    		-webkit-animation-delay: 0.6s;
    		-moz-animation-delay: 0.6s;
    }
    #fountainG_2 {
    	left: 29px;
    	animation-delay: 0.75s;
    		-o-animation-delay: 0.75s;
    		-ms-animation-delay: 0.75s;
    		-webkit-animation-delay: 0.75s;
    		-moz-animation-delay: 0.75s;
    }
    #fountainG_3 {
    	left: 58px;
    	animation-delay: 0.9s;
    		-o-animation-delay: 0.9s;
    		-ms-animation-delay: 0.9s;
    		-webkit-animation-delay: 0.9s;
    		-moz-animation-delay: 0.9s;
    }
    #fountainG_4 {
    	left: 88px;
    	animation-delay: 1.05s;
    		-o-animation-delay: 1.05s;
    		-ms-animation-delay: 1.05s;
    		-webkit-animation-delay: 1.05s;
    		-moz-animation-delay: 1.05s;
    }
    #fountainG_5 {
    	left: 117px;
    	animation-delay: 1.2s;
    		-o-animation-delay: 1.2s;
    		-ms-animation-delay: 1.2s;
    		-webkit-animation-delay: 1.2s;
    		-moz-animation-delay: 1.2s;
    }
    #fountainG_6 {
    	left: 146px;
    	animation-delay: 1.35s;
    		-o-animation-delay: 1.35s;
    		-ms-animation-delay: 1.35s;
    		-webkit-animation-delay: 1.35s;
    		-moz-animation-delay: 1.35s;
    }
    #fountainG_7 {
    	left: 175px;
    	animation-delay: 1.5s;
    		-o-animation-delay: 1.5s;
    		-ms-animation-delay: 1.5s;
    		-webkit-animation-delay: 1.5s;
    		-moz-animation-delay: 1.5s;
    }
    #fountainG_8 {
    	left: 205px;
    	animation-delay: 1.64s;
    		-o-animation-delay: 1.64s;
    		-ms-animation-delay: 1.64s;
    		-webkit-animation-delay: 1.64s;
    		-moz-animation-delay: 1.64s;
    }
    @keyframes bounce_fountainG {
    	0% {
    	transform: scale(1);
    		background-color: rgb(0,0,0);
    	}
    	100% {
    	transform: scale(.3);
    		background-color: rgb(255,255,255);
    	}
    }
    @-o-keyframes bounce_fountainG {
    	0% {
    	-o-transform: scale(1);
    		background-color: rgb(0,0,0);
    	}
    	100% {
    	-o-transform: scale(.3);
    		background-color: rgb(255,255,255);
    	}
    }
    @-ms-keyframes bounce_fountainG {
    	0% {
    	-ms-transform: scale(1);
    		background-color: rgb(0,0,0);
    	}
    	100% {
    	-ms-transform: scale(.3);
    		background-color: rgb(255,255,255);
    	}
    }
    @-webkit-keyframes bounce_fountainG {
    	0% {
    	-webkit-transform: scale(1);
    		background-color: rgb(0,0,0);
    	}
    	100% {
    	-webkit-transform: scale(.3);
    		background-color: rgb(255,255,255);
    	}
    }
    @-moz-keyframes bounce_fountainG {
    	0% {
    	-moz-transform: scale(1);
    		background-color: rgb(0,0,0);
    	}
    	100% {
    	-moz-transform: scale(.3);
    		background-color: rgb(255,255,255);
    	}
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
                                                    <select name="filter_chart" id="filter_chart" class="form-control select2">
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
                                                <canvas id="pieChart" data-url="top-chart" data-key="<?= $model->acc_id ?>" style="height:250px" value="wow"></canvas>
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
                                            <h3 class="box-title">Receipt History</h3>
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
                                                    'sna_receipt_date',
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
                                        <?=
                                        GridView::widget([
                                            'id' => 'list_redemption',
                                            'options' => [
                                                'style' => 'font-size: 13px',
                                            ],
                                            'layout' => '{items} {summary} {pager}',
                                            'dataProvider' => $redeemProvider,
                                            'columns' => [
                                                [
                                                    'label' => 'Merchant',
                                                    'attribute' => 'lph_com_id',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if (!empty($data->merchant)) {
                                                            return $data->merchant->com_name . ($data->merchant->com_joined == 1 ? ' <i class="fa fa-check"></i>' : '');
                                                        }
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'lph_datetime',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDatetime($data->lph_datetime);
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'lph_amount',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDecimal($data->lph_amount);
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'lph_type',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return $data->lph_type == 'C' ? 'Credit' : 'Debet';
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'lph_total_point',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDecimal($data->lph_total_point);
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'lph_expired',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        return Yii::$app->formatter->asDatetime($data->lph_expired);
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
                                                        return Yii::$app->formatter->asDatetime($data->svo_datetime);
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'promotion.del_start',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->promotion))
                                                            return Yii::$app->formatter->asDatetime($data->promotion->del_start);
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'promotion.del_end',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->promotion))
                                                            return Yii::$app->formatter->asDatetime($data->promotion->del_end);
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
                                                        return Yii::$app->formatter->asDatetime($data->svr_datetime);
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'posVoucher.pvo_valid_start',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->posVoucher))
                                                            return Yii::$app->formatter->asDatetime($data->posVoucher->pvo_valid_start);
                                                    }
                                                ],
                                                [
                                                    'attribute' => 'posVoucher.pvo_valid_end',
                                                    'format' => 'html',
                                                    'value' => function($data) {
                                                        if(!empty($data->posVoucher))
                                                            return Yii::$app->formatter->asDatetime($data->posVoucher->pvo_valid_end);
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
