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

$this->title = 'Review Merchant Signup: ' . $model->mer_bussines_name;
$this->registerJsFile('https://maps.google.com/maps/api/js?sensor=true', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/gmaps/gmaps.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
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
                        <?= $form->field($model, 'mer_bussiness_description')->textArea(); ?>
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
                        <?= $form->field($model_company, 'com_in_mall')->checkBox(['style' => 'margin-top:10px;'], false)->label('In Mall?') ?>
                        <?= $form->field($model, 'mer_address')->textInput(); ?>
                        <?= $form->field($model, 'mer_post_code')->textInput(); ?>
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
					                "typeahead:select" => "function(ev, suggestion) { $('#company-mall_id').val(suggestion.id); }",
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

                        <div class="form-group" id="merchantsignup-map">
                            <label class="col-sm-3 control-label">Map</label>
                            <div class="col-sm-8">
                                <div id="map" style="height:300px"></div>
                            </div>
                        </div>

                        <?= $form->field($model, 'mer_contact_phone')->textInput(); ?>
                        <?= $form->field($model, 'mer_office_fax')->textInput(); ?>
                        <?= $form->field($model_company, 'com_website')->textInput([$model->mer_website]); ?>
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
                                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Copy to Company', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary pull-right' , 'disabled' => true]) ?>
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
$this->registerCssFile("https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/css/base/minified/jquery-ui.min.css");

$this->registerJs("
    var isUpdate = 0;

    var PostCodeid = '#merchantsignup-mer_address';
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

    $(document).ready(function () {
        var baseUrl = '" . Yii::$app->homeUrl . "';

        $('.field-company-com_latitude').hide();
        $('.field-company-com_longitude').hide();

        mall = " . $inMall . ";
        mall_checked = (mall == 1 ? true : false);
        function loadMall() {
            $('#merchantsignup-map').hide();
            $('.field-merchantsignup-mer_address').hide();
            $('.field-merchantsignup-mer_post_code').hide();
            $('.field-company-com_city').hide();
            $('.field-company-mall_name').show();
        }
        function unloadMall() {
            $('#merchantsignup-map').show();
            $('.field-merchantsignup-mer_address').show();
            $('.field-merchantsignup-mer_post_code').show();
            $('.field-company-com_city').show();
            $('.field-company-mall_name').hide();
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

        $('.datepicker').datepicker({
          autoclose: true
        });

        initialize();

        //triger modal for image-croper
        $('.eb-cropper').on('click',function(){
            $('#cropper-modal').modal({show: true});
        });

        loadRegister('EBC');

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
            

        //});
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



