<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use app\components\helpers\Utc;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Dashboard';
$this->registerCss("
    .invoice {
        position: relative;
        background: #eaeaea;
        border: 1px solid #f4f4f4;
        padding: 20px;
        margin-bottom: 10px;
    }
    .page-header {
        padding-bottom: 9px;
        border-bottom: 1px solid #00C0EF;
    }
    .page-header span {
        font-size: 17px;
        margin-left: 20px;
    }
    .bg-malaysia {
        background-color: #3c8dbc;
        padding: 0px 50px;
    }
    .bg-indonesia {
        background-color: #f56954;
        padding: 0px 50px;
    }
    ");
?>
<section class="content-header ">
    <h1><?= $this->title?></h1>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid">
                <div class="form-group pull-right">
                    <label>Date range</label><br>
                    <form role="form" class="form-inline" method="get" action="/dashboard">
                        <div class="input-group">
                            <div class="input-group-addon" for="reservation">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="dash_daterange" class="form-control pull-right" id="the_daterange" value="<?= (!empty($_GET['dash_daterange'])) ? $_GET['dash_daterange'] :''; ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-area-chart"></i> 
                    <span>Malaysia : </span> <span class="bg-malaysia">&nbsp;</span>  <span>Indonesia : </span> <span class="bg-indonesia">&nbsp;</span>
                    <small class="pull-right">Receipt Upload</small>
                </h2>
            </div>
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
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Week Year</th>
                                    <th>Unique User</th>
                                    <th>User MY</th>
                                    <th>User ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dataProvider->getModels() as $row): ?>
                                    <tr>
                                        <td><?= $row['weeks'] ?></td>
                                        <td><?= $row['total_unique'] ?></td>
                                        <td><?= $row['total_unique_user_my'] ?></td>
                                        <td><?= $row['total_unique_user_id'] ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
<?php /*= GridView::widget([
'dataProvider' => $dataProvider->getModels(),
// 'dataProvider' => $dataProvider,
'layout' => '{items} {summary} {pager}',
'columns' => [
[
'label' => 'Week Year',
'attribute' => 'sna_upload_date',
'value' => function($data){
return str_replace("-00",'',$data->weeks);
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
'label' => 'User MY',
'attribute' => 'sna_acc_id',
'value' => function($data){
return $data->total_unique_user_my;
}
],
[
'label' => 'User ID',
'attribute' => 'sna_acc_id',
'value' => function($data){
return $data->total_unique_user_id;
}
],
],
'tableOptions' => ['class' => 'table table-striped table-hover']
]); */?>
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
                            <canvas id="lineChart" data-url="/dashboard/line-chart?dash_daterange=<?= (!empty($_GET['dash_daterange'])) ? $_GET['dash_daterange'] :''; ?>"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-area-chart"></i>
                    <span>Malaysia : </span> <span class="bg-malaysia">&nbsp;</span>  <span>Indonesia : </span> <span class="bg-indonesia">&nbsp;</span>
                    <small class="pull-right">Receipt Reviewed</small>
                </h2>
            </div>
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
                            <canvas id="lineRejectChart" data-url="/dashboard/line-chart?dash_daterange=<?= (!empty($_GET['dash_daterange'])) ? $_GET['dash_daterange'] :''; ?>"></canvas>
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
                            <canvas id="lineApproveChart" data-url="/dashboard/line-chart?dash_daterange=<?= (!empty($_GET['dash_daterange'])) ? $_GET['dash_daterange'] :''; ?>"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerJsFile($this->theme->baseUrl.'/dist/customes/js/dashboard.js',['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
?>
