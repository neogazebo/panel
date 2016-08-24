<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\modal;
use yii\helpers\Url;

$this->title = '"' . $model->com_name . '" Merchants List';
$this->registerCssFile(Yii::$app->homeUrl . 'common/js/plugins/waitme/waitMe.css');
$this->registerJsFile(Yii::$app->homeUrl . 'common/js/plugins/waitme/waitMe.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/js/plugins/tablesorter/dist/js/jquery.tablesorter.min.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile(Yii::$app->homeUrl . 'pages/MerchantHqManager.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border"></div>
                <form id="add-merchant-child">
                    <div class="box-body">
                        <div id="searchList" class="col-sm-6">
                            <h2><?= $this->title ?></h2>
                            <select name="children[]" id="search_merchant_to" class="form-control" size="8" multiple="multiple">
                                <?php foreach($merchant_children as $children): ?>
                                    <option value="<?= $children->com_id ?>"><?= $children->com_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-1" style="margin-top: 43px">
                            <h2></h2>
                            <button type="button" id="search_merchant_rightAll" class="btn btn-default btn-block"><i class="fa fa-backward"></i></button>
                            <button type="button" id="search_merchant_rightSelected" class="btn btn-default btn-block"><i class="fa fa-chevron-left"></i></button>
                            <button type="button" id="search_merchant_leftSelected" class="btn btn-default btn-block"><i class="fa fa-chevron-right"></i></button>
                            <button type="button" id="search_merchant_leftAll" class="btn btn-default btn-block"><i class="fa fa-forward"></i></button>
                        </div>
                        <div id="searchSelected" class="col-sm-5">
                            <h2>Merchants List</h2>
                            <input id="search-merchant" class="form-control" type="text" placeholder="Search unregistered merchants..." name="q">
                            <select id="search_merchant" name="from[]" class="form-control" size="8" multiple="multiple"></select>
                        </div>
                        <div class="clearfix"></div>    
                    </div>
                    <div class="box-footer">
                        <input type="hidden" class="com_id" name="com_id" value="<?= $model->com_id ?>">
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Back', ['index'], ['class' => 'btn btn-warning']) ?>
                        <?= Html::submitButton('<i class="fa fa-check"></i> Save', ['class' => 'btn btn-primary pull-right']) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
