<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use app\components\helpers\Utc;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Dashboard';
?>
<section class="content-header ">
    <h1><?= $this->title?></h1>
</section>
<section class="content">
    <div class="row">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Receipt Upload Unique User</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '{items} {summary} {pager}',
                    'columns' => [
                        [
                            'label' => 'Week Year',
                            'attribute' => 'sna_upload_date',
                            'value' => function($data){
                                return $data->weeks;
                            }
                        ],
                        [
                            'label' => 'Unique User',
                            'attribute' => 'sna_acc_id',
                            'value' => function($data){
                                return $data->total_unique;
                            }
                        ],
                        [
                            'label' => 'Total User',
                            'attribute' => 'sna_acc_id',
                            'value' => function($data){
                                return $data->total_user;
                            }
                        ]
                    ],
                    'tableOptions' => ['class' => 'table table-striped table-hover']
                ]); ?>
            </div>
        </div>
    </div>
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Receipt Upload Per Country</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="lineChart" data-url="dashboard/line-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Receipt Rejected Per Country</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="lineRejectChart" data-url="dashboard/line-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Receipt Approved Per Country</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="lineApproveChart" data-url="dashboard/line-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$this->registerJsFile($this->theme->baseUrl.'/dist/customes/js/dashboard.js',['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
 ?>
