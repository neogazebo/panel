<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\DatePicker;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\helpers\Json;

$this->title = 'Review Merchant Signup: ' . $model->mer_bussines_name;
$this->registerJsFile('https://maps.google.com/maps/api/js?sensor=true');
$this->registerJsFile($this->theme->baseUrl . '/plugins/gmaps/gmaps.js');
$latitude = ($model_company->com_latitude ? $model_company->com_latitude : 3.139003);
$longitude = ($model_company->com_longitude ? $model_company->com_longitude : 101.686855);
$inMall = (isset($model_company->com_in_mall) && $model_company->com_in_mall == 1 ? 1 : 0);
?>

<div id="wrap">
    <div class="container">
        <div id="page-heading">
            <h1><i class="fa fa-briefcase"></i> <?= Yii::t('app', $this->title) ?></h1>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><i class="fa fa-briefcase"></i> <?= Yii::t('app', $this->title); ?></h4>
                    </div>
                    <?php
                    $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal'],
                        'enableAjaxValidation'=>true,
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ],
                    ]);
                    ?>
                    <div class="panel-body" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
                        <?= $form->field($model, 'mer_login_email')->textInput(); ?>
                        <?= $form->field($model, 'mer_company_name')->textInput(); ?>
                        <?= $form->field($model, 'mer_bussines_name')->textInput(); ?>
                        <?= $form->field($model, 'mer_bussiness_description')->textArea(); ?>
                        <?= $form->field($model_company, 'com_subcategory_id')->dropDownList($model_company->categoryListData); ?>
                        <div id="tagging">
                            <div class="form-group" id="business-tag">
                                <label class="col-sm-3 control-label">Tags</label>
                                <div class="col-lg-8">
                                    <span class="btn btn-sm btn-primary" id="add_tag" data-tag=""><i class="fa fa-plus"></i> Add Tag</span>
                                    <?php
                                    $tagged = $model_company->getTag($model_company->com_id);
                                    $tags = '';
                                    foreach($tagged as $tag)
                                        $tags .= $tag->tag_id . ',';
                                    ?>
                                    <input type="hidden" id="company-tag" name="Company[tag]" value="<?= $tags ?>">
                                    <div id="tagging-list">
                                        <?php
                                        foreach($tagged as $tag):
                                        ?>
                                        <div style="background: #ececec; border: 1px solid #ccc; margin: 3px 0; padding: 5px">
                                            <?= $tag->tag_name ?>
                                            <a href="javascript:;" data-id="<?= $tag->tag_id ?>" class="pull-right remove-tag"><i class="fa fa-times"></i></a>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?= $form->field($model_company, 'com_in_mall')->checkBox(['style' => 'margin-top:10px;'], false)->label('In Mall?') ?>
                        <?= $form->field($model, 'mer_address')->textInput(); ?>
                        <?= $form->field($model, 'mer_post_code')->textInput(); ?>
                        <?php
                        $url = \yii\helpers\Url::to(['/merchant-signup/default/select2']);

                        $initScript = <<< SCRIPT
                            function (element, callback) {
                                id = $('#company-mall_id').val();
                                if (id !== "") {
                                    $.ajax("{$url}?id=" + id, {
                                        dataType: "json",
                                    }).done(function(data) { callback(data.results); });
                                }
                            }
SCRIPT;
                        echo $form->field($model_company, 'mall_id')->widget(kartik\widgets\Select2::classname(), [
                            'options' => ['placeholder' => 'Choose a Mall ...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new yii\web\JsExpression("function(term,page) { return { search: term }; }"),
                                    'results' => new yii\web\JsExpression('function(data,page) { return { results:data.results }; }'),
                                ],
                                'initSelection' => new yii\web\JsExpression($initScript)
                            	],
                        ]);
                        ?>

                        <?=
                        $form->field($model_company, 'com_city')->widget(kartik\widgets\Typeahead::classname(), [
                            'options' => ['placeholder' => 'City, Region, Country', 'id' => 'location'],
                            'pluginOptions' => ['highlight' => true],
                            'dataset' => [
                                [
                                    'remote' => yii\helpers\Url::to(['city/list']) . '?q=%QUERY',
                                    'limit' => 10
                                ]
                            ],
                            'pluginEvents' => [
                                'typeahead:selected' => 'function(evt,data) {}',
                            ]
                        ])->hint(Html::a(Html::img(Yii::$app->homeUrl . 'img/btn-plus.png', ['data-action' => 'destination', 'class' => 'find-address-book'])));
                        ?>

                        <div class="form-group" id="merchantsignup-map">
                            <label class="col-sm-3 control-label">Map</label>
                            <div class="col-sm-8">
                                <div id="map" style="height:300px"></div>
                            </div>
                        </div>

                        <?= $form->field($model, 'mer_contact_phone')->textInput(); ?>
                        <?= $form->field($model, 'mer_office_fax')->textInput(); ?>
                        <?= $form->field($model, 'mer_website')->textInput(); ?>
                        <?= $form->field($model_company, 'com_size')->dropDownList($model_company->companySizeListData); ?>
                        <?= $form->field($model_company, 'com_nbrs_employees')->dropDownList($model_company->numberEmployeeListData); ?>
                        <?= $form->field($model_company, 'com_fb')->textInput(); ?>
                        <?= $form->field($model_company, 'com_twitter')->textInput(); ?>
                        <?= $form->field($model_company, 'com_timezone')->dropDownList($model_company->timeZoneListData) ?>
                        <?= $form->field($model_company, 'com_reg_num')->textInput() ?>

                        <div class="form-group">
                            <?= Html::activeLabel($model, 'fes_id', ['class' => 'col-lg-3 control-label']) ?>
                            <div class="col-lg-8">
                                <select name="Company[fes_id]" id="company-fes_id" class="form-control"></select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Photo</label>
                            <div class="col-xs-8">
                                <input type="hidden" id="com_photo" name="Company[com_photo]" value="<?= $model_company->com_photo ?>">
                                <a data-toggle="modal" data-image="com_logo" data-field="com_photo" href="#" class="eb-cropper">
                                    <?php $image = !empty($model_company->com_photo) ? Yii::$app->params['businessUrl'] . $model_company->com_photo : Yii::$app->params['imageUrl'] . 'default-image.jpg' ?>
                                    <img src="<?= $image ?>" id="com_logo" class="img-responsive" width="240">
                                </a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Banner</label>
                            <div class="col-xs-8">
                                <input type="hidden" id="com_banner" name="Company[com_banner_photo]" value="<?= $model_company->com_banner_photo ?>">
                                <a data-toggle="modal" data-image="com_banner_photo" data-field="com_banner" href="#" class="eb-cropper">
                                    <?php $image = !empty($model_company->com_banner_photo) ? Yii::$app->params['businessUrl'] . $model_company->com_banner_photo : Yii::$app->params['imageUrl'] . 'default-image.jpg' ?>
                                    <img src="<?= $image ?>" id="com_banner_photo" class="img-responsive" width="240">
                                </a>
                            </div>
                        </div>

                        <?= $form->field($model_company, 'com_latitude')->hiddenInput()->label('') ?>
                        <?= $form->field($model_company, 'com_longitude')->hiddenInput()->label('') ?>

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Copy to Company', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary pull-right', 'disabled' => true]) ?>
                                    <button type="reset" class="pull-left btn" onclick="window.location = '<?= Yii::$app->urlManager->createUrl('business/index') ?>'"><i class="fa fa-times"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$hiddenTag = '';
foreach($model_company->getTag($model_company->com_id) as $tag) {
    $hiddenTag .= $tag->tag_id.',';
}

$this->registerJs("
    var hiddenTag = '".$hiddenTag."',
        isUpdate = 1;

    $('.datepicker').datepicker();
    $(document).ready(function () {
        var baseUrl = '" . Yii::$app->homeUrl . "',
            mall = " . $inMall . ",
            type = " . Yii::$app->user->identity->type . ",
            mal_id = " . (!empty($model_company->modelMallMerchant->mam_mal_id) ? $model_company->modelMallMerchant->mam_mal_id : 0) . ";
        

        $('.field-company-com_mac_id').hide();
        $('.field-company-com_latitude').hide();
        $('.field-company-com_longitude').hide();
        if(type == 1)
            $('.field-company-com_registered_to').show();
        else
            $('.field-company-com_registered_to').hide();

        mall_checked = (mall == 1 ? true : false);
        function loadMall() {
            $('#businessMap').slideUp();
            $('.field-company-com_mac_id').show();
            $('.field-company-com_address').hide();
            $('.field-company-com_postcode').hide();
            $('.field-company-com_city').hide();
            $('.field-company-mall_id').show();
            $('#floor-unit').show();
        }
        function unloadMall() {
            $('#businessMap').slideDown();
            $('.field-company-com_mac_id').hide();
            $('.field-company-com_address').show();
            $('.field-company-com_postcode').show();
            $('.field-company-com_city').show();
            $('.field-company-mall_id').hide();
            $('.field-mallmerchant-mam_floor').hide();
            $('.field-mallmerchant-mam_unit_number').hide();
            $('#floor-unit').hide();
        }
        function checkOrNot(mall_checked) {
            if(mall_checked)
                loadMall();
            else
                unloadMall();
        }
        checkOrNot(mall_checked);
        $('#company-com_in_mall').each(function() {
            $(this).click(function() {
                var checked = $(this).is(':checked');
                checkOrNot(checked);
            });
        });

        var item = [];
        function updateCategory() {
            var id = $('#company-com_id').val() == undefined ? 0 : $('#company-com_id').val();
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: baseUrl + 'business/getcategory/' + id,
                success: function(result) {
                    $('#list-category').append('<li class=\'category-data\'>' + result.mac_name + ' <a href=\'javascript:;\'><i class=\'fa fa-times\'></i></a></li>');
                }
            });
        }
        // add a category
        $('#save').click(function() {
            if($('#category').val() != '' ) {
                $('#list-category').append('<li class=\'category-data\'>' + $('#category').val() + ' <a href=\'javascript:;\'><i class=\'fa fa-times\'></i></a></li>');
                $('#category').val('');
                item.push($('#category').val());
            }
            return false;
        });

        // remove a category
        $(document).on('click', 'ul#list-category li a', function() {
            $(this).parent().remove();
        });

        //set the mall id on button add floor and to determine whether to show the multifloor or single floor field
        $('#company-mall_id').on('change',function(){
            $('#add_floor').attr('data-mall',$(this).val());
            $.ajax({
                type : 'POST',
                dataType: 'json',
                url: baseUrl + 'business/ismallmanaged/',
                data: {mall_id: $(this).val()},
                success: function (res) {
                    if (res == 1)
                    {
                        $('#nomallkey').addClass('hide');
                        $('#hasmallkey').removeClass('hide');
                    }
                    else if (res == 0)
                    {
                        $('#nomallkey').removeClass('hide');
                        $('#hasmallkey').addClass('hide');
                    }
                }
            });
        });

        //triger modal for image-croper
        $('.eb-cropper').on('click',function(){
            $('#cropper-modal').modal({show: true});
        });
    }
    

// setup map autocomplete and dragable

var PostCodeid = '#company-com_address';
        var longval = '#company-com_longitude';
        var latval = '#company-com_latitude';
        var geocoder;
        var map;
        var marker;
        
        function initialize() {
            // init map
            var initialLat = $(latval).val();
            var initialLong = $(longval).val();
            if (initialLat == '') {
                initialLat = ".$latitude.";
                initialLong = " . $longitude . ";
            }
            var latlng = new google.maps.LatLng(initialLat, initialLong);
            var options = {
                zoom: 16,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                heading: 90,
                tilt: 45
            };   
        
            map = new google.maps.Map(document.getElementById('map'), options);
        
            geocoder = new google.maps.Geocoder();    
        
            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                position: latlng
            });
        
            google.maps.event.addListener(marker, 'dragend', function (event) {
                var point = marker.getPosition();
                map.panTo(point);
            });
            
        };
        
        $(document).ready(function () {
        
            initialize();

            $(function () {
                $(PostCodeid).autocomplete({
                    //This bit uses the geocoder to fetch address values
                    source: function (request, response) {
                        geocoder.geocode({ 'address': request.term }, function (results, status) {
                            response($.map(results, function (item) {
                                return {
                                    label: item.formatted_address,
                                    value: item.formatted_address
                                };
                                
                            }));
                        });
                    }
                });
            });
        
            $(PostCodeid).keyup(function (e) {
                var address = $(PostCodeid).val();
                geocoder.geocode({ 'address': address }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        marker.setPosition(results[0].geometry.location);
                        $(latval).val(marker.getPosition().lat());
                        $(longval).val(marker.getPosition().lng());
                    }
//                    else {
//                        alert('Geocode was not successful for the following reason: ' + status);
//                    }
                });
                e.preventDefault();
            });
        
            //Add listener to marker for reverse geocoding
            google.maps.event.addListener(marker, 'drag', function () {
                geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            $(latval).val(marker.getPosition().lat());
                            $(longval).val(marker.getPosition().lng());
                        }
                    }
                });
            });
            
//            function rotate90() {
//                var heading = map.getHeading() || 0;
//                map.setHeading(heading + 90);
//            }
//
//            function autoRotate() {
//            alert(map.getTilt())
//                // Determine if we're showing aerial imagery.
//                if (map.getTilt() !== 0) {
//                  window.setInterval(rotate90, 3000);
//              }
//            }
        });
", yii\web\View::POS_END, "company-update");


Modal::begin([
    'header' => '<h4>Add Floor / Unit</h4>',
    'id' => 'modal-add-floor'
]);
?>
<div class="form-group" id="floor-select">
    <select class="form-control" id="floor">
    </select>
    <br />
    <select class="form-control" id="unit"></select>
    <div>
        <div class="help-block"></div>
    </div>
</div>
<div class="form-group">
    <?= Html::submitButton('Save', ['id' => 'save-floor', 'class' => 'btn btn-success']) ?>
</div>
<?php
Modal::end();

Modal::begin([
    'header' => '<h4>Add Business Tagging</h4>',
    'id' => 'modal-tag'
]);
?>
<div class="form-group" id="tag-select">
    <select class="form-control" id="tag"></select>
    <div>
        <div class="help-block"></div>
    </div>
</div>
<div class="form-group">
    <?= Html::submitButton('<i class="fa fa-check"></i> Save', ['id' => 'save-tag', 'class' => 'btn btn-success']) ?>
</div>
<?php
Modal::end();

Modal::begin([
    'header' => '<h4>Unit list</h4>',
    'id' => 'modal-unit'
]);

echo '<div id="grid-container"></div>';

Modal::end();
?>



