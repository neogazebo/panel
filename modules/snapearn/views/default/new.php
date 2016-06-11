<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

$form = ActiveForm::begin([
    'id' => 'create-form',
    'options' => ['class' => 'form-group'],
    'enableAjaxValidation' => true,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ]
]);
?>
<div class="modal-body">
    <?= $form->field($company, 'com_name')->textInput() ?>
    <?= $form->field($company, 'com_business_name')->textInput() ?>
    <?= $form->field($company, 'com_email')->textInput() ?>
    <?= $form->field($company, 'com_subcategory_id')->dropDownList($company->categoryList); ?>
    <?= $form->field($company, 'com_in_mall')->checkBox(['style' => 'margin-top:10px;'], false)->label('In Mall?') ?>
    <?php
    $city_url = \yii\helpers\Url::to(['/city/list']);
    $cityDesc = empty($company->com_city_id) ? '' : City::findOne($model->com_city_id)->cit_name;
    echo $form->field($company, 'com_city')->widget(kartik\widgets\Select2::classname(), [
        'initValueText' => $cityDesc,
        'options' => ['placeholder' => 'City, Region, Country', 'tab-index' => false],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 2,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => $city_url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return { q: params.term }; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ]
    ]);
    ?>

    <?= $form->field($company, 'com_postcode')->textInput(); ?>
    <?= $form->field($company, 'com_address')->textInput(); ?>

    <?php
    $mall_url = \yii\helpers\Url::to(['/mall/default/list']);
    $mallDesc = empty($company->mall_id) ? '' : Mall::findOne($model->mall_id)->mal_name;
    echo $form->field($company, 'mall_id')->widget(kartik\widgets\Select2::classname(), [
        'initValueText' => $mallDesc,
        'options' => ['placeholder' => 'Search for mall ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 2,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => $mall_url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return { q: params.term }; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(mall) { return mall.text; }'),
            'templateSelection' => new JsExpression('function (mall) { return mall.text; }'),
        ],
        // 'options' => ['placeholder' => 'Choose a Mall ...'],
        // 'pluginOptions' => [
        //     'allowClear' => true,
        //     'minimumInputLength' => 1,
        //     'ajax' => [
        //         'url' => $url,
        //         'dataType' => 'json',
        //         'data' => new yii\web\JsExpression("function(term,page) { return { search:term }; }"),
        //         'results' => new yii\web\JsExpression('function(data,page) { return { results:data.results }; }'),
        //     ],
        //     'initSelection' => new yii\web\JsExpression($initScript)
        // ],
        // 'pluginEvents' => [
        //     'select2-focus' => "function() {
        //         var baseUrl = '" . Yii::$app->homeUrl . "',
        //             id = $(this).val();
        //         $('#company-com_mac_id').html('');
        //         $.ajax({
        //             type: 'GET',
        //             url: baseUrl + 'business/mallcategory/?id=' + id,
        //             dataType: 'json',
        //             success: function(result) {
        //                 var options = '';
        //                 for(var i = 0; i < result.length; i++) {
        //                     options += '<option value=' + result[i].mac_id + '>' + result[i].mac_name + '</option>';
        //                 }
        //                 $('#company-com_mac_id').html(options);
        //             }
        //         });
        //     }",
        // ],
    ]);
    ?>
    <?= $form->field($company, 'com_mac_id')->dropDownList([]) ?>
    <div class="form-group clearfix" id="businessMap">
        <label class="col-sm-3 control-label">Map</label>
        <div class="col-sm-12">
            <div id="map" style="height:300px"></div>
        </div>
    </div>
    <div id="floor-unit">
        <div class="form-group hide" id="hasmallkey">
            <label class="col-sm-3 control-label">&nbsp;</label>
            <div class="col-lg-6">
                <span class="btn btn-sm btn-primary" id="add_floor" data-mall="">Add Floor / Unit</span>
                <br />
                <table class="table" id="tbl_list_floor">
                    <thead>
                        <tr>
                            <th>
                                Floor
                            </th>
                            <th>
                                Unit
                            </th>
                            <th width="5%">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="nomallkey" class="hide">
            <?= $form->field($company->modelMallMerchant, 'mam_floor')->textInput() ?>
            <?= $form->field($company->modelMallMerchant, 'mam_unit_number')->textInput() ?>
        </div>
    </div>
    <?= $form->field($company, 'com_gst_enabled')->checkBox(['style' => 'margin-top:10px;'], false)->label('Gst?') ?>
    <?= $form->field($company, 'com_gst_id')->textInput() ?>
    <?= $form->field($company, 'fes_id')->dropDownList($company->featureSubscription) ?>
    <?= $form->field($company, 'com_point')->textInput(['value' => 0]); ?>
    <?= $form->field($company, 'com_latitude')->hiddenInput()->label('') ?>
    <?= $form->field($company, 'com_longitude')->hiddenInput()->label('') ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'pull-right btn btn-info pull-right']) ?>
</div>
<?php
ActiveForm::end();

$latitude = ($company->com_latitude ? $company->com_latitude : 3.139003);
$longitude = ($company->com_longitude ? $company->com_longitude : 101.686855);
$this->registerJs("
    var mall_checked = 0;
    $('#create-business').hide();
    $('#company-com_point').popover();
    $('.field-company-com_latitude').hide();
    $('.field-company-com_longitude').hide();

    var loadMap = function() {
        map = new GMaps({
            div: '#map',
            zoom: 13,
            lat: " . $latitude . ",
            lng: " . $longitude . ",
            click: function(e) {
                map.removeMarkers();
                map.addMarker({
                    lat: e.latLng.lat(),
                    lng: e.latLng.lng(),
                });
                $('#company-com_latitude').val(e.latLng.lat());
                $('#company-com_longitude').val(e.latLng.lng());
            },
        });
        map.addMarker({
            lat: " . $latitude . ",
            lng: " . $longitude . ",
        });
        $('#company-com_address').keyup(function(e) {
            e.preventDefault();
            GMaps.geocode({
                address: $('#company-com_address').val().trim(),
                callback: function(results, status) {
                    if (status == 'OK') {
                        map.removeMarkers();
                        var latLng = results[0].geometry.location;
                        map.setCenter(latLng.lat(), latLng.lng());
                        map.addMarker({
                            lat: latLng.lat(),
                            lng: latLng.lng()
                        });
                        $('#company-com_latitude').val(latLng.lat());
                        $('#company-com_longitude').val(latLng.lng());
                    }
                }
            });
        });
    };

    $('.datepicker').datepicker();
    var loadMall = function() {
        $('#businessMap').css('display','none');
        $('.field-company-com_mac_id').show();
        // $('.field-company-com_subcategory_id').hide();
        $('.field-company-com_address').hide();
        $('.field-company-com_postcode').hide();
        $('.field-company-com_city').hide();
        $('.field-company-mall_id').show();
        $('#floor-unit').show();
    };
    var unloadMall = function() {
        $('#businessMap').css('display','block');
        $('.field-company-com_mac_id').hide();
        // $('.field-company-com_subcategory_id').show();
        $('.field-company-com_address').show();
        $('.field-company-com_postcode').show();
        $('.field-company-com_city').show();
        $('.field-company-mall_id').hide();
        $('#floor-unit').hide();
    };
    var checkOrNot = function(mall_checked) {
        if(mall_checked)
            loadMall();
        else
            unloadMall();
    };
    checkOrNot(mall_checked);
    $('#company-com_in_mall').each(function() {
        $(this).click(function() {
            var checked = $(this).is(':checked');
            checkOrNot(checked);
            if(checked == false)
                loadMap();
        });
    });

    $('.modal-title').text('New Merchant');
",yii\web\View::POS_END, 'snapearn-form');
?>
