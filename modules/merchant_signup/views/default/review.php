<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use kartik\widgets\DatePicker;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\helpers\Json;

$this->title = $model->mer_bussines_name;
$this->registerJsFile('https://maps.google.com/maps/api/js?sensor=true', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/gmaps/gmaps.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerCssFile($this->theme->baseUrl . '/dist/plugins/jQueryui/jquery-ui.min.css');
$latitude = ($model_company->com_latitude ? $model_company->com_latitude : 3.139003);
$longitude = ($model_company->com_longitude ? $model_company->com_longitude : 101.686855);
$model_company->com_in_mall = true;
?>

<section class="content-header">
    <h1>Review Merchant Signup </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <div class="box-tools">

                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
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
                        <?= $form->field($model_company, 'com_email')->textInput(['value' => $model->mer_login_email]); ?>
                        <?= $form->field($model_company, 'com_name')->textInput(['value' => $model->mer_company_name]); ?>
                        <?= $form->field($model_company, 'com_business_name')->textInput(['value' => $model->mer_bussines_name]); ?>
                        <?= $form->field($model_company, 'com_description')->textArea(['value' => $model->mer_bussiness_description]); ?>
                        <?= $form->field($model_company, 'com_subcategory_id')->dropDownList($model_company->categoryListData); ?>
                        <div id="tagging">
                            <div class="form-group" id="business-tag">
                                <label class="col-sm-3 control-label">Tags</label>
                                <div class="col-lg-8">
                                    <span class="btn btn-sm btn-primary" id="add_tag" data-tag=""><i class="fa fa-plus"></i> Add Tag</span>
                                    <input type="hidden" id="company-tag" name="Company[tag]" value="">
                                    <div id="tagging-list"></div>
                                </div>
                            </div>
                        </div>
                        <?= $form->field($model_company, 'com_in_mall')->checkBox(['style' => 'margin-top:10px;'])->label('In Mall?') ?>
						<?= 
					        $form->field($model_company, 'mall_name')->widget(Typeahead::classname(),[
					            'name' => 'merchant',
					            'options' => [
					                'placeholder' => 'Mall Name'
					            ],
					            'pluginOptions' => [
					                'highlight'=>true,
					                'minLength' => 3
					            ],
					            'pluginEvents' => [
					                "typeahead:select" => "function(ev, suggestion) { $('#mall_id').val(suggestion.id); }",
					            ],
					            'dataset' => [
					                [
					                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('id')",
					                    'display' => 'value',
					                    'remote' => [
					                        'url' => Url::to(['mall-list']) . '?q=%QUERY',
					                        'wildcard' => '%QUERY'
					                    ],
					                    'limit' => 20
					                ]
					            ]
					        ])->label('Mall Name');
						?>
                        <input id="mall_id" type="hidden" name="mall_id" value="">
                        <?= $form->field($model_company, 'com_address')->textInput(['value' => $model->mer_address]); ?>
                        <?= $form->field($model_company, 'com_postcode')->textInput(['value' => $model->mer_post_code]); ?>
						<?= 
					        $form->field($model_company, 'com_city')->widget(Typeahead::classname(),[
					            'name' => 'merchant',
					            'options' => ['placeholder' => 'City, Region, Country'],
					            'pluginOptions' => [
					                'highlight'=>true,
					                'minLength' => 3
					            ],
					            'pluginEvents' => [
					                "typeahead:select" => "function(ev, suggestion) { $(this).val(suggestion.id); }",
					            ],
					            'dataset' => [
					                [
					                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('id')",
					                    'display' => 'value',
					                    'remote' => [
					                        'url' => Url::to(['city-list']) . '?q=%QUERY',
					                        'wildcard' => '%QUERY'
					                    ],
					                    'limit' => 20
					                ]
					            ]
					        ]);
						?>
                        
                        <div class="form-group" id="businessMap">
                            <label class="col-sm-3 control-label">Map</label>
                            <div class="col-sm-8">
                                <div id="map" style="height:300px"></div>
                            </div>
                        </div>

                        <?= $form->field($model_company, 'com_phone')->textInput(['value' => $model->mer_contact_phone]); ?>
                        <?= $form->field($model_company, 'com_fax')->textInput(['value' => $model->mer_office_fax]); ?>
                        <?= $form->field($model_company, 'com_website')->textInput(['value' => $model->mer_website]); ?>
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
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Copy to Company', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary pull-right']) ?>
                                    <button type="reset" class="pull-left btn" onclick="window.location = '<?= Yii::$app->urlManager->createUrl('merchant-signup') ?>'"><i class="fa fa-times"></i> Cancel</button>
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
$this->registerJs("
    var isUpdate = 0;

    function loadRegister(reg)
    {
        $('select#company-fes_id').empty();
        $.ajax({
            type: 'GET',
            url: baseUrl + 'merchant-signup/default/register',
            data: { reg: reg },
            success: function(result) {
                var comfes = $('select#company-fes_id');
                comfes.empty();
                comfes.append(result);
            }
        });
    }
    loadRegister('EBC');
   //triger modal for image-croper
    $('.eb-cropper').on('click',function(){
        $('#cropper-modal').modal({show: true});
    });

    var mall_checked = 1;
    $('#create-business').hide();
    $('#company-com_point').popover();
    $('.field-company-com_latitude').hide();
    $('.field-company-com_longitude').hide();

    $('#create').click(function() {
        $('#create-business').show();
        $.ajax({
            type: 'GET',
            url: baseUrl + 'business/register',
            data: { reg: 'EBC' },
            cache: false,
            success: function(result) {
                $('#company-fes_id').empty().append(result);
            }
        });

        var com_name = $('#companysuggestion-cos_name').val();
        $('#company-com_name').val(com_name);
        $('#company-com_business_name').val(com_name);
        loadMap();
        if($('#companysuggestion-cos_mall').val() != '') {
            $('#company-com_in_mall').attr('checked', true);
            mall_checked = 1;
            loadMall();
        } else {
            unloadMall();
        }

        $('html, body').animate({
            scrollTop: $('#create-business').offset().top
        }, 1000);
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
        $('.field-mallmerchant-mam_mal_id').show();
        $('.field-company-mall_name').show();
        $('#floor-unit').show();
    };
    var unloadMall = function() {
        $('#businessMap').css('display','block');
        $('.field-company-com_mac_id').hide();
        // $('.field-company-com_subcategory_id').show();
        $('.field-company-com_address').show();
        $('.field-company-com_postcode').show();
        $('.field-company-com_city').show();
        $('.field-mallmerchant-mam_mal_id').hide();
        $('.field-company-mall_name').hide();
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
            if(checked == false){
                $('.field-company-com_in_mall > div').find('input').val(0);
                $(this).val(0);
                initialize();
            }else{
                $('.field-company-com_in_mall > div').find('input').val(1);
                $(this).val(1);
            }
        });
    });



    // $(document).ready(function () {
    //     var baseUrl = '" . Yii::$app->homeUrl . "';

    //     $('.field-company-com_latitude').hide();
    //     $('.field-company-com_longitude').hide();

    // var loadMall = function() {
    //     $('#businessMap').css('display','none');
    //     $('.field-company-com_mac_id').show();
    //     // $('.field-company-com_subcategory_id').hide();
    //     $('.field-company-com_address').hide();
    //     $('.field-company-com_postcode').hide();
    //     $('.field-company-com_city').hide();
    //     $('.field-mallmerchant-mam_mal_id').show();
    //     $('.field-company-mall_name').show();
    //     $('#floor-unit').show();
    // };
    // var unloadMall = function() {
    //     $('#businessMap').css('display','block');
    //     $('.field-company-com_mac_id').hide();
    //     // $('.field-company-com_subcategory_id').show();
    //     $('.field-company-com_address').show();
    //     $('.field-company-com_postcode').show();
    //     $('.field-company-com_city').show();
    //     $('.field-mallmerchant-mam_mal_id').hide();
    //     $('.field-company-mall_name').hide();
    //     $('#floor-unit').hide();
    // };
    //     function checkOrNot(mall_checked) {
    //         if(mall_checked)
    //             loadMall();
    //         else
    //             unloadMall();
    //     }
    //     checkOrNot(mall_checked);
    //     $('#company-com_in_mall').each(function() {
    //             $(this).click(function() {
    //                 var checked = $(this).is(':checked');
    //                 checkOrNot(checked);
    //                 if(checked == false){
    //                     $(this).val(0);
    //                     initialize();
    //                 }else{
    //                     $(this).val(1);
    //                 }
    //             });
    //         });

    //     $('.datepicker').datepicker({
    //       autoclose: true
    //     });

    //     initialize();

    //     //triger modal for image-croper
    //     $('.eb-cropper').on('click',function(){
    //         $('#cropper-modal').modal({show: true});
    //     });

    //     loadRegister('EBC');
    // });

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
        });   
", \yii\web\View::POS_END, 'merchantsignup-review');

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

?>

<?php $this->registerJsFile($this->theme->baseUrl . '/js/business-tag.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]); ?>

<?php
// start the widget
echo \app\components\widgets\ImageCropper::widget([
    'wsmall' => 200, // width small
    'hsmall' => 134, // height small
    'wbig' => 600, // width big
    'hbig' => 400, // height big
    'ratio' => 1.5, // ratio dimension crop box
    'skipAndResize' => true, // true or false, if false => original image will be duplicated, if true original image will be resized to wbig x hbig
    'prefix' => 'img-', // for file name prepix
]);



