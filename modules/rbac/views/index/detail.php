<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\modal;
use yii\helpers\Url;

$this->title = 'Permission List of "'.$title.'"';
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::button('<i class="fa fa-refresh"></i> Refresh Permission List', ['value' => Url::to(['get-permission?name='.$title]), 'class' => 'ajaxRequest btn btn-primary btn-sm', 'data-key' => Url::current()]) ?>
                </div>
                <form id="addChild" action="<?= Url::to(['add-child']) ?>" data-key="<?= $title; ?>" method="post">
                    <div class="box-body">
                        <div id="searchList" class="col-sm-6">
                            <h2><?= $title ?> Permission</h2>
                            <select name="to[]" id="search_to" class="form-control" size="8" multiple="multiple">
                            <?php foreach($models as $model): ?>
                                <option value="<?= $model['child'] ?>"><?= $model['child'] ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-1" style="margin-top: 43px">
                            <h2></h2>
                            <button type="button" id="search_rightAll" class="btn btn-default btn-block"><i class="fa fa-backward"></i></button>
                            <button type="button" id="search_rightSelected" class="btn btn-default btn-block"><i class="fa fa-chevron-left"></i></button>
                            <button type="button" id="search_leftSelected" class="btn btn-default btn-block"><i class="fa fa-chevron-right"></i></button>
                            <button type="button" id="search_leftAll" class="btn btn-default btn-block"><i class="fa fa-forward"></i></button>
                        </div>
                        <div id="searchSelected" class="col-sm-5">
                            <h2>Permission list</h2>
                            <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
                            <?php foreach($lists as $list): ?>
                                <option value="<?= $list['name'] ?>"><?= $list['name'] ?></option>
                            <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="role" value="<?= $title ?>">
                        </div>
                        <div class="clearfix"></div>    
                    </div>
                    <div class="box-footer">
                        <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'btn btn-warning']) ?>
                        <?= Html::submitButton('<i class="fa fa-check"></i> Save', ['class' => 'btn btn-primary pull-right']) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
