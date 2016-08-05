<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\bootstrap\modal;
use yii\helpers\Url;

$this->title = '"' . $title->spg_name . '" List';
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border"></div>
                <form id="addChild" action="<?= Url::to(['add-list']) ?>" data-key="<?= $title->spg_name; ?>" method="post">
                    <div class="box-body">
                        <div id="searchList" class="col-sm-6">
                            <h2><?= $this->title ?></h2>
                            <input type="hidden" name="spg_id" value="<?= $title->spg_id ?>">
                            <select name="to[]" id="search_to" class="form-control" size="8" multiple="multiple">
                            <?php foreach($models as $model): ?>
                                <option value="<?= $model->spgd_usr_id ?>"><?= $model->user->username ?></option>
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
                            <h2>Operator List</h2>
                            <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
                            <?php foreach($users as $user): ?>
                                <option value="<?= $user->id ?>"><?= $user->username ?></option>
                            <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="List" value="<?= $this->title ?>">
                        </div>
                        <div class="clearfix"></div>    
                    </div>
                    <div class="box-footer">
                        <?= Html::a('<i class="fa fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::submitButton('<i class="fa fa-check"></i> Save', ['class' => 'btn btn-primary pull-right']) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
