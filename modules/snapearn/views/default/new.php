<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

$this->registerJsFile('https://maps.google.com/maps/api/js?sensor=true', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/gmaps/gmaps.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$latitude = ($model->company->com_latitude ? $model->company->com_latitude : 3.139003);
$longitude = ($model->company->com_longitude ? $model->company->com_longitude : 101.686855);
$urlCity = \yii\helpers\Url::to(['city/list']);

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
    <?= $form->field($model->company, 'com_name')->textInput() ?>
    <?= $form->field($model->company, 'com_business_name')->textInput() ?>
    <?= $form->field($model->company, 'com_email')->textInput() ?>
    <?= $form->field($model->company, 'com_subcategory_id')->dropDownList($model->company->categoryListData); ?>
    <?= $form->field($model->company, 'com_in_mall')->checkBox(['style' => 'margin-top:10px;'], false)->label('In Mall?') ?>
    <?= $form->field($model->company, 'com_city')->widget(kartik\widgets\Select2::classname(), [
        'options' => ['placeholder' => 'Search for a city ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => $urlCity,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return { q: params.term }; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ], 
    ]);
    ?>

    <?= $form->field($model->company, 'com_postcode')->textInput(); ?>
    <?= $form->field($model->company, 'com_address')->textInput(); ?>
    <?php
    $url = \yii\helpers\Url::to(['/merchant/mall/select2']);
    $mal_id = $model->company->modelMallMerchant->mam_mal_id;
    $initScript = <<< SCRIPT
        function (element, callback) {
            var id = "{$mal_id}";
            if (id !== "") {
                \$.ajax("{$url}?id=" + id, {
                    dataType: "json"
                }).done(function(data) { callback(data.results); });
            }
        }
SCRIPT;
    echo $form->field($model->company, 'mall_id')->widget(kartik\widgets\Select2::classname(), [
        'options' => ['placeholder' => 'Choose a Mall ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new yii\web\JsExpression("function(term,page) { return { search:term }; }"),
                'results' => new yii\web\JsExpression('function(data,page) { return { results:data.results }; }'),
            ],
            'initSelection' => new yii\web\JsExpression($initScript)
        ],
        'pluginEvents' => [
            'select2-focus' => "function() {
                var baseUrl = '" . Yii::$app->homeUrl . "',
                    id = $(this).val();
                $('#company-com_mac_id').html('');
                $.ajax({
                    type: 'GET',
                    url: baseUrl + 'business/mallcategory/?id=' + id,
                    dataType: 'json',
                    success: function(result) {
                        var options = '';
                        for(var i = 0; i < result.length; i++) {
                            options += '<option value=' + result[i].mac_id + '>' + result[i].mac_name + '</option>';
                        }
                        $('#company-com_mac_id').html(options);
                    }
                });
            }",
        ],
    ]);
    ?>
    <?= $form->field($model->company, 'com_mac_id')->dropDownList([]) ?>
    <div class="form-group" id="businessMap">
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
            <?= $form->field($model->company->modelMallMerchant, 'mam_floor')->textInput() ?>
            <?= $form->field($model->company->modelMallMerchant, 'mam_unit_number')->textInput() ?>
        </div>
    </div>
    <?= $form->field($model->company, 'com_phone')->textInput(); ?>
    <?= $form->field($model->company, 'com_gst_enabled')->checkBox(['style' => 'margin-top:10px;'], false)->label('Gst?') ?>
    <?= $form->field($model->company, 'com_gst_id')->textInput() ?>
    <?= $form->field($model->company, 'fes_id')->dropDownList($model->company->featureSubscription) ?>
    <?= $form->field($model->company, 'com_point')->textInput(['value' => 0]); ?>
    <?= $form->field($model->company, 'com_latitude')->hiddenInput()->label('') ?>
    <?= $form->field($model->company, 'com_longitude')->hiddenInput()->label('') ?>
</div>

<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'pull-right btn btn-info pull-right']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
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

    $('#existing').click(function() {
         $('#business_exist').modal({ show: true });
         return false;
    });

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

    $('.status').change(function() {
        if($(this).val() == 1) {
            $('.reject-form').css('display', 'none');
            $('.point-form').css('display', 'block');
        } else if($(this).val() == 2) {
            $('.point-form').css('display', 'none');
            $('.reject-form').css('display', 'block');
            $('.sna_amount, .sna_point, .sna_status').val('');
        } else {
            $('.reject-form').css('display', 'none');
            $('.point-form').css('display', 'none');
            $('.sna_amount, .sna_point, .sna_status').val('');
        }
    }).trigger('change');

    $('#snapearn-sna_amount').blur(function() {
        var point = Math.floor($('#snapearn-sna_amount').val());
        $.ajax({
            type: 'POST',
            url: baseUrl + 'loyaltypoint/ajax-snapearn-point',
            data: { id: id, com_id: com_id, point: point },
            dataType: 'json',
            success: function(result) {
                $('#snapearn-sna_point').val(result);
            }
        });
    });

    $('.submit-button, .reset-button').click(function(){
        $('#saveNext').val(0);
    });
",yii\web\View::POS_END, 'snapearn-form');
?>
