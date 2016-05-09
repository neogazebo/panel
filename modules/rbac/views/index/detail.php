<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\modal;
use yii\helpers\Url;
use kartik\sortable\Sortable;

$this->title = 'Permission List of "'.$title.'"';
$this->registerCssFile(Yii::$app->urlManager->baseUrl.'/themes/AdminLTE/dist/plugins/multi-select/multi-select.css',['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::button('<i class="fa fa-refresh"></i> &nbsp; Update Permission List',['value' => Url::to('get-permission'),'class' => 'ajaxRequest btn btn-primary btn-sm','data-key' => Url::current()]) ?>
                </div>
                <div class="box-body">
                    <div id="searchList" class="col-sm-5">
                        <h2><?= $title ?> Permission</h2>
                        <select name="to[]" id="search_to" class="form-control" size="8" multiple="multiple">
                        <?php foreach ($models as $model) : ?>
                            <option value="<?= $model['child'] ?>"><?= $model['child'] ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <h2></h2>
                        <button type="button" id="search_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                        <button type="button" id="search_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                        <button type="button" id="search_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                        <button type="button" id="search_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                    </div>
                    <div id="searchSelected" class="col-sm-5">
                        <h2>Permission list</h2>
                        <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
                        <?php foreach ($lists as $list) : ?>
                            <option value="<?= $list['name'] ?>"><?= $list['name'] ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="clearfix"></div>    
                </div>
                <div class="box-footer">
                    <?= Html::resetButton('Cancel', ['class' => 'btn btn-warning']) ?>
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary pull-right']) ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$this->registerJsFile(Yii::$app->urlManager->baseUrl.'/themes/AdminLTE/dist/plugins/multi-select/jquery.multi-select.js',['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$inlineScript = "
$('#listRole').multiSelect();
$('div.ms-selectable').prepend('<h3>All Permission</h3>');
$('div.ms-selection').prepend('<h3> ".$title." Permission</h3>');
";
$this->registerJs($inlineScript, View::POS_END, 'inline-js');
?>