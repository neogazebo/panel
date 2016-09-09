<?php

use app\components\helpers\Utc;
use app\models\Account;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$this->title = 'Snap & Earn List';

$search = !empty(Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
$visible = Yii::$app->user->identity->superuser == 1 ? true : false;

$company_name = null;

if($data_hooks)
{
    if(isset($data_hooks['company']))
    {
        $company_name = $data_hooks['company']->com_name;
    }
}

$this->registerCssFile($this->theme->baseUrl.'/plugins/jQueryUI/jquery-ui.min.css');
$this->registerCssFile($this->theme->baseUrl.'/plugins/jQueryUI/jquery-ui.theme.min.css');
$this->registerJs("var search_mechant_url = '" . Url::to(['list']) . "';", \yii\web\View::POS_BEGIN);
$this->registerJs("var search_member_email_url = '" . Url::to(['search-member-email']) . "';", \yii\web\View::POS_BEGIN);
$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'pages/SnapEarnManager.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
?>
<section class="content-header">
    <h1><?= $this->title?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <form class="form-inline" role="form" method="get" action="/snapearn">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="form-group">
                                <label>Country</label>
                                <select name="sna_cty" class="form-control select2" style="width: 100%;">
                                      <option value="" <?= (!empty($_GET['sna_cty']) == '' || empty($_GET['sna_cty'])) ? 'selected' : '' ?>>All</option>
                                      <option value="ID" <?= (!empty($_GET['sna_cty']) && $_GET['sna_cty'] == 'ID') ? 'selected' : '' ?>>Indonesia</option>
                                      <option value="MY" <?= (!empty($_GET['sna_cty']) && $_GET['sna_cty'] == 'MY') ? 'selected' : '' ?>>Malaysia</option>
                                </select>
                                </div>
                                <div class="form-group">
                                <label>Receipt Status</label>
                                <select name="sna_status" class="form-control select2" style="width: 100%;">
                                      <option value="" <?= (!empty($_GET['sna_status']) && $_GET['sna_status'] == '' || empty($_GET['sna_status'])) ? 'selected' : '' ?>>All</option>
                                      <option value="NEW" <?= (!empty($_GET['sna_status']) && $_GET['sna_status'] == 'NEW') ? 'selected' : '' ?>>New</option>
                                      <option value="APP" <?= (!empty($_GET['sna_status']) && $_GET['sna_status'] == 'APP') ? 'selected' : '' ?>>Approved</option>
                                      <option value="REJ" <?= (!empty($_GET['sna_status']) && $_GET['sna_status']== 'REJ') ? 'selected' : '' ?>>Rejected</option>
                                </select>
                                </div>
                                <div class="form-group">
                                    <label>Date range</label><br>
                                    <div class="input-group">
                                        <div class="input-group-addon" for="reservation">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="sna_daterange" class="form-control pull-right" id="the_daterange" value="<?= (!empty($_GET['sna_daterange'])) ? $_GET['sna_daterange'] : '' ?>">
                                    </div>
                                </div>
                                <?php if ($visible) : ?>
                                <div class="form-group">
                                    <label for="receipt">Receipt</label><br>
                                    <input name="sna_receipt" class="form-control" id="receipt" placeholder="Receipt number" type="text" value="<?= (!empty($_GET['sna_receipt'])) ? $_GET['sna_receipt'] : '' ?>">
                                </div>  
                                <div class="form-group">
                                    <label for="ddd">Member</label>
                                    <div>
                                    <?php
                                        $data = Account::find()
                                        ->select(['acc_screen_name as value','acc_id as id'])
                                        ->asArray()
                                        ->all();
                                        $member_value = (!empty($_GET['member'])) ? $_GET['member'] : '';
                                        echo AutoComplete::widget([
                                            'name' => 'member',
                                            'id' => 'sna_member_name',
                                            'value' => $member_value,
                                            'options' => [
                                                'class' => 'form-control',
                                                'placeholder' => 'Member Name'
                                            ],
                                            'clientOptions' => [
                                                'source' => $data,
                                                'autoFill'=>true,
                                                'minLength'=>'3',
                                                'select' => new JsExpression("function( event, ui ) {
                                                    $('#sna_member').val(ui.item.id);
                                                 }")
                                            ],
                                         ]);
                                    ?>
                                        <input name="sna_member" class="form-control" id="sna_member" placeholder="Enter name" type="hidden" value="<?= (!empty($_GET['sna_member'])) ? $_GET['sna_member'] : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Member Email</label><br>
                                    <input name="member_email"  type="text" id="member_email_search" class="form-control" value="<?= (!empty($_GET['member_email'])) ? $_GET['member_email'] : '' ?>" width="300px" placeholder="Enter Email" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="form-group">
                                    <label>Operator</label>
                                    <?php
                                    $ops = (!empty($_GET['operator'])) ? $_GET['operator'] : 'Operator';
                                    $mer = (!empty($_GET['merchant'])) ? $_GET['merchant'] : 'Merchant';
                                    ?>
                                    <?=
                                        Typeahead::widget([
                                            'id' => 'operator',
                                            'name' => 'operator',
                                            'options' => ['placeholder' => $ops],
                                            'pluginOptions' => [
                                                'highlight' => true,
                                                'minLength' => 3
                                            ],
                                            'pluginEvents' => [
                                                "typeahead:select" => "function(ev, suggestion) { "
                                                    . "$('#ops_name').val(suggestion.id); "
                                                    . "$(this).css('color','000');"
                                                . "}",
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
                            </div>
                            <div class="form-group">
                                <label>Merchant</label><br>
                                <?php 
                                    /*
                                    echo Typeahead::widget([
                                        'id' => 'merchant',
                                        'name' => 'merchant',
                                        //'options' => ['placeholder' => $mer],
                                        'pluginOptions' => [
                                            'highlight' => true,
                                            'minLength' => 3
                                        ],
                                        'pluginEvents' => [
                                            "typeahead:select" => "function(ev, suggestion) { "
                                                . "$('#com_name').val(suggestion.id); "
                                                . "$(this).css('color','000');"
                                            . "}",
                                        ],
                                        'dataset' => [
                                            [
                                                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('id')",
                                                'display' => 'value',
                                                'remote' => [
                                                    'url' => Url::to(['list']) . '?q=%QUERY',
                                                    'wildcard' => '%QUERY'
                                                ],
                                                'limit' => 20
                                            ]
                                        ]
                                    ]);
                                    */
                                ?>
                                <input type="text" id="com_name_search" class="form-control" value="<?= $company_name ?>" width="200px" placeholder="Enter merchant name" />
                            </div>
                            <input type="hidden" name="ops_name" id="ops_name" value="<?= (!empty($_GET['ops_name'])) ? $_GET['ops_name'] : '' ?>">
                            <input type="hidden" name="com_name" id="com_name" value="<?= (!empty($_GET['com_name'])) ? $_GET['com_name'] : '' ?>">
                            <?php endif; ?>  
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <button name="output_type" value="view" type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                            </div>
                            <?php if ($visible) : ?>
                            <div class="form-group">
                                <label>Export</label><br>
                                <button name="output_type" value="excel" type="submit" class="btn btn-info btn-flat"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                            </div>
                            <?php endif; ?>  
                        </div>
                    </div>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'layout' => '{summary} {items} {pager}',
                            'columns' => [
                                [
                                    'label' => 'Merchant',
                                    'attribute' => 'sna_com_id',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return (!empty($data->merchant)) ? $data->merchant->com_name : '<a class=""><span class="not-set">(not set)</span></a>';
                                    },
                                    'contentOptions'=>['style'=>'max-width: 90px; word-wrap: break-word;']
                                ],
                                [
                                    'label' => 'Member',
                                    'visible' => $visible,
                                    'format' => 'html',
                                    'attribute' => 'sna_acc_id',
                                    'value' => function($data) {
                                        return (!empty($data->member)) ? $data->member->acc_screen_name : '<a class=""><span class="not-set">(not set)</span></a>';
                                    }
                                ],
                                [
                                    'label' => 'Member Email',
                                    'visible' => $visible,
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return (!empty($data->member)) ? $data->member->acc_facebook_email : '<a class=""><span class="not-set">(not set)</span></a>';
                                    }
                                ],
                                [
                                    'label' => 'Receipt Number',
                                    'visible' => $visible,
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return (!empty($data->sna_ops_receipt_number)) ? $data->sna_ops_receipt_number : '<a class=""><span class="not-set">(not set)</span></a>';
                                    },
                                    'contentOptions'=>['style'=>'max-width: 80px; word-wrap: break-word;']
                                ],
                                [
                                    'attribute' => 'sna_receipt_amount',
                                    'format' => ['decimal', 2],
                                    'value' => function($data) {
                                        return $data->sna_receipt_amount;
                                    }
                                ],
                                [
                                    'attribute' => 'sna_point',
                                    'format' => ['decimal', 0],
                                    'value' => function($data) {
                                        return $data->sna_point;
                                    }
                                ],
                                [
                                    'attribute' => 'sna_upload_date',
                                    'format' => ['date', 'php:d-m-Y H:i:s'],
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime(\app\components\helpers\Utc::convert($data->sna_upload_date));
                                    }
                                ],
                                [
                                    'label' => 'Date Review',
                                    'attribute' => 'sna_review_date',
                                    'value' => function($data) {
                                        if (!empty($data->sna_review_date)) {
                                            return Yii::$app->formatter->asDateTime(Utc::convert($data->sna_review_date));
                                        }
                                        
                                    }
                                ],
                                [
                                    'label' => 'Operator',
                                    'attribute' => 'sna_review_by',
                                    'value' => function($data) {
                                        if (!empty($data->review)) {
                                            return $data->review->username;
                                        } 
                                    }
                                ],
                                [
                                    'label' => 'Status',
                                    'attribute' => 'sna_status',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if ($data->sna_status == 1) {
                                            return "<i class='fa fa-check approved-status'></i>";
                                        } elseif ($data->sna_status == 2) {
                                            return "<i class='fa fa-close rejected-status'></i>";
                                        } else {
                                            return "New";
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Description',
                                    'attribute' => 'sna_sem_id',
                                    'value' => function($data){
                                        if (!empty($data->remark)) {
                                            return $data->remark->sem_remark;
                                        }
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{update}</span>',
                                    'buttons' => [
                                        'update' => function($url,$model) {
                                            $superuser = Yii::$app->user->identity->superuser;
                                            if ($model->sna_status == 0) {
                                                return Html::a('<i class="fa fa-pencil-square-o"></i>', ['to-update', 'id' => $model->sna_id]);
                                            } elseif($model->sna_status != 0 && $superuser == 1) {
                                                return Html::a('<i class="fa fa-pencil-square-o btn-correction"></i>', ['correction/to-correction','id' => $model->sna_id]);
                                            }
                                        },
                                    ],
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]);
                    ?>
                    </div>
                </div>
            </div>
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
    var operator = getParameterByName('operator'),
        merchant = getParameterByName('merchant');
    
    // set value operator name
    if (operator != '') {
        $('#operator').val(operator);
    }
    
    $('#operator').on('blur', function() {
        if ($(this).val() === '') {
            $('#ops_name').val('');
        }
    });

    // set value merchant name
    if (merchant != '') {
        $('#merchant').val(merchant);
    }
    
    $('#merchant').on('blur', function() {
        if ($(this).val() === '') {
            $('#com_name').val('');
        }
    });

    $('#sna_member_name').on('blur', function() {
        if ($(this).val() === '') {
            $('#sna_member').val('');
        }
    });
", yii\web\View::POS_END, 'snapearn-list');
?>
