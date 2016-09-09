<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\components\helpers\Utc;

$this->title = 'Merchant Inquiry List';
$search = !empty(Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
$dataProvider->sort->attributes['category.com_category'] = [
    'asc' => ['category.com_category' => SORT_ASC],
    'desc' => ['category.com_category' => SORT_DESC],
];
$dataProvider->sort->attributes['userCreated.username'] = [
    'asc' => ['userCreated.username' => SORT_ASC],
    'desc' => ['userCreated.username' => SORT_DESC],
];
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <form class="form-inline" role="form" method="get" action="/merchant-signup/inquiry">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="form-group">
                                    <label>Date range</label><br>
                                    <div class="input-group">
                                        <div class="input-group-addon" for="reservation">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="com_daterange" class="form-control pull-right" id="the_daterange" value="<?= (!empty($_GET['com_daterange'])) ? $_GET['com_daterange'] : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Operator</label>
                                    <?php
                                        $ops = (!empty($_GET['operator'])) ? $_GET['operator'] : 'Operator';
                                        echo kartik\widgets\Typeahead::widget([
                                            'id' => 'operator',
                                            'name' => 'operator',
                                            'options' => ['placeholder' => $ops],
                                            'pluginOptions' => [
                                                'highlight' => true,
                                                'minLength' => 3
                                            ],
                                            'pluginEvents' => [
                                                'typeahead:select' => 'function(ev, suggestion) { '
                                                    . '$("#ops_name").val(suggestion.id);'
                                                    . '$(this).css("color", "000");'
                                                . '}',
                                            ],
                                            'dataset' => [
                                                [
                                                    'datumTokenizer' => 'Bloodhound.tokenizers.obj.whitespace("id")',
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
                                    <label>Merchant</label>
                                    <?php
                                        $company = (!empty($_GET['merchant'])) ? $_GET['merchant'] : 'Merchant';
                                        echo kartik\widgets\Typeahead::widget([
                                            'id' => 'merchant',
                                            'name' => 'merchant',
                                            'options' => ['placeholder' => $company],
                                            'pluginOptions' => [
                                                'highlight' => true,
                                                'minLength' => 3
                                            ],
                                            'pluginEvents' => [
                                                'typeahead:select' => 'function(ev, suggestion) { '
                                                    . '$("#com_name").val(suggestion.id);'
                                                    . '$(this).css("color", "000");'
                                                . '}',
                                            ],
                                            'dataset' => [
                                                [
                                                    'datumTokenizer' => 'Bloodhound.tokenizers.obj.whitespace("id")',
                                                    'display' => 'value',
                                                    'remote' => [
                                                        'url' => Url::to(['list']) . '?q=%QUERY',
                                                        'wildcard' => '%QUERY'
                                                    ],
                                                    'limit' => 20
                                                ]
                                            ]
                                        ]);
                                    ?>
                                </div>
                            </div>
                            <input type="hidden" name="ops_name" id="ops_name" value="<?= (!empty($_GET['ops_name'])) ? $_GET['ops_name'] : '' ?>">
                            <input type="hidden" name="com_name" id="com_name" value="<?= (!empty($_GET['com_name'])) ? $_GET['com_name'] : '' ?>">
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <button name="output_type" value="view" type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                            </div>
                            <div class="form-group">
                                <label>Export</label><br>
                                <button name="output_type" value="excel" type="submit" class="btn btn-info btn-flat"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                            </div>
                        </div>
                    </form>
                </div> <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'merchant-inquiry',
                            'dataProvider' => $dataProvider,
                            'layout' => '{items} {summary} {pager}',
                            'columns' => [
                                'com_name',
                                'com_email',
                                'com_city',
                                'category.com_category',
                                [
                                    'attribute' => 'com_created_date',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDate(Utc::convert($data->com_created_date));
                                    }
                                ],
                                'userCreated.username'
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]);
                        ?>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>

<?php
$this->registerJs("
    // get parameter from url params name
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    
    // set var 
    var operator = getParameterByName('operator');
    
    // set value operator name
    if (operator != '') {
        $('#operator').val(operator);
    }
    
    $('#operator').on('blur', function() {
        if ($(this).val() === '') {
            $('#ops_name').val('');
        }
    });
", yii\web\View::POS_END, 'merchant-inquiry');
?>
