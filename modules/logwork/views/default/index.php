<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Working Hours';
$customCss = <<< SCRIPT
    .table > thead > tr > th, 
    .table > tbody > tr > th, 
    .table > tfoot > tr > th, 
    .table > thead > tr > td, 
    .table > tbody > tr > td, 
    .table > tfoot > tr > td  {
        border-top: 1px solid #f4f4f4;
        text-align: right;
    }
SCRIPT;
$this->registerCss($customCss);
?>
<section class="content-header ">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="pull-left form-inline">
                        <div class="form-group">
                            <label>Configuration</label><br>
                            <?= Html::a('<i class="fa fa-wrench"></i> &nbsp; Working Hour Point', ['/logwork/point'], ['class' => 'btn btn-flat btn-danger']) ?>
                        </div>
                    </div>
                    <form role="form" class="form-inline" method="get" action="/logwork">
                        <div class="pull-right">
                            <div class="form-group">
                                <label>Snap &amp; Earn Group</label>
                                <?= 
                                    Typeahead::widget([
                                        'name' => 'sna_group',
                                        'options' => ['placeholder' => 'Find Group'],
                                        'pluginOptions' => [
                                            'highlight' => true,
                                            'minLength' => 2
                                        ],
                                        'pluginEvents' => [
                                            "typeahead:select" => "function(ev, suggestion) { $('#group').val(suggestion.id); }",
                                        ],
                                        'dataset' => [
                                            [
                                                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('id')",
                                                'display' => 'value',
                                                'remote' => [
                                                    'url' => Url::to('/snapearn/group/list') . '?q=%QUERY',
                                                    'wildcard' => '%QUERY'
                                                ],
                                                'limit' => 20
                                            ]
                                        ]
                                    ]);
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <?= 
                                    Typeahead::widget([
                                        'name' => 'username',
                                        'options' => ['placeholder' => 'Find User'],
                                        'pluginOptions' => [
                                            'highlight' => true,
                                            'minLength' => 3
                                        ],
                                        'pluginEvents' => [
                                            "typeahead:select" => "function(ev, suggestion) { $('#wrk_by').val(suggestion.id); }",
                                        ],
                                        'dataset' => [
                                            [
                                                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('id')",
                                                'display' => 'value',
                                                'remote' => [
                                                    'url' => Url::to(['user-list']) . '?q=%QUERY',
                                                    'wildcard' => '%QUERY'
                                                ],
                                                'limit' => 20
                                            ]
                                        ]
                                    ]);
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Date range</label><br>
                                <div class="input-group">
                                    <div class="input-group-addon" for="reservation">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="wrk_daterange" class="form-control pull-right" id="the_daterange" value="<?= (!empty($_GET['wrk_daterange'])) ? $_GET['wrk_daterange'] : '' ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label>Country</label>
                                     <select class="form-control" name="country">
                                         <option value="" <?= (empty($_GET['country'])) ? 'selected' : '' ?>>All</option>
                                         <option value="ID" <?= (!empty($_GET['country']) && $_GET['country'] == 'ID') ? 'selected' : '' ?>>Indonesia</option>
                                         <option value="MYR" <?= (!empty($_GET['country']) && $_GET['country'] == 'MYR') ? 'selected' : '' ?>>Malaysia</option>
                                     </select>
                                 </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="group" id="group">
                                <input type="hidden" name="wrk_by" id="wrk_by">
                                <input type="hidden" name="wrk_param_id" id="wrk_param_id">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'layout' => '{summary} {items} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'label' => 'Username',
                                    'attribute' => 'user.username',
                                    'value' => function($data) {
                                        if (!empty($data['user']))
                                            return $data['user']['username'];
                                    }
                                ],
                                [
                                    'label' => 'Total Approved',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['total_approved'], 0);
                                    }
                                ],
                                [
                                    'label' => 'Total Rejected',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['total_rejected'], 0);
                                    }
                                ],
                                [
                                    'label' => 'Rejection Rate',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asPercent($data['rejected_rate']);
                                    }
                                ],
                                [
                                    'label' => 'Total Point',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['total_point'], 0);
                                    }
                                ],
                                [
                                    'label' => 'Total Work Time',
                                    'value' => function($data) {
                                        date_default_timezone_set('UTC');
                                        return date('H:i:s', $data['total_record']);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{detail} </span>',
                                    'buttons' => [
                                        'detail' => function($url,$model) {
                                            return Html::a('<i class="fa fa-search"></i>', ['view', 'id' => $model['wrk_by'],'wrk_daterange' => (!empty($_GET['wrk_daterange'])) ? $_GET['wrk_daterange'] : '' ]);
                                        },
                                    ]
                                ]
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>