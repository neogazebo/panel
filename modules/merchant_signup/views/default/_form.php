<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Company;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;

$this->registerJsFile('https://maps.google.com/maps/api/js?sensor=true', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/gmaps/gmaps.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$latitude = ($model_company->com_latitude ? $model_company->com_latitude : 3.139003);
$longitude = ($model_company->com_longitude ? $model_company->com_longitude : 101.686855);
$inMall = (isset($model_company->com_in_mall) && $model_company->com_in_mall == 1 ? 1 : 0);

$this->registerJs("var baseUrl = '" . Yii::$app->homeUrl . "';", \yii\web\View::POS_HEAD);

/* @var $this yii\web\View */
/* @var $model_merchant_signup app\model_merchant_signups\MerchantSignup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="merchant-signup-form">

<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-info">
                <div class="box-body">
                

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_company_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_bussiness_description')->textarea(['rows' => 6]) ?>

                <?= $form->field($model_company, 'com_subcategory_id')->dropDownList($model_company->categoryListData); ?>

                <div id="tagging">
                    <div class="form-group field-merchantsignup-tag" id="business-tag">
                        <label class="control-label" for="merchantsignup-tag">Tags</label>
                        <br/>
                        <span class="btn btn-sm btn-primary" id="add_tag" data-tag=""><i class="fa fa-plus"></i> Add Tag</span>
                        <input type="hidden" id="company-tag" name="Company[tag]" value="">
                        <div id="tagging-list"></div>

                        <div class="help-block"></div>
                    </div>
                </div>

                <?=
                $form->field($model_company, 'com_city')->widget(kartik\widgets\Typeahead::classname(), [
                    'options' => ['placeholder' => 'City, Region, Country', 'id' => 'location'],
                    'pluginOptions' => ['highlight' => true],
                    'dataset' => [
                        [
                            'remote' => [
                                'url' => yii\helpers\Url::to(['/merchant-signup/default/city-list']) . '?q=%QUERY',
                                'wildcard' => '%QUERY'
                            ],
                            'limit' => 10,
                            'display' => 'value',
                        ]
                    ],
                    'pluginEvents' => [
                        'typeahead:selected' => 'function(evt,data) {}',
                    ]
                ]);
                ?>

                <?= $form->field($model_merchant_signup, 'mer_address')->textInput() ?>

                <div class="form-group field-merchantsignup-map" id="businessMap">
                    <label class="control-label" for="merchantsignup-map">Map</label>
                    <div class="col-sm-12">
                        <div id="map" style="height:300px"></div>
                    </div>
                    <div class="help-block"></div>
                </div>

                <?= $form->field($model_company, 'com_latitude')->hiddenInput()->label('') ?>
                <?= $form->field($model_company, 'com_longitude')->hiddenInput()->label('') ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_type_retail')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_type_service')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_type_franchise')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_type_pro_services')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_post_code')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_office_phone')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_office_fax')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_website')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_multichain')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_multichain_file')->textarea(['rows' => 6]) ?>

                <?= $form->field($model_merchant_signup, 'mer_login_email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_pic_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_contact_phone')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_contact_mobile')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_contact_email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_preferr_comm_mail')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_preferr_comm_email')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_preferr_comm_mobile_phone')->textInput() ?>


                <div class="form-group">
                    <?= Html::submitButton($model_merchant_signup->isNewRecord ? 'Create' : 'Update', ['class' => $model_merchant_signup->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</section>

</div>


<?php
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

    $(document).ready(function () {
        var baseUrl = '" . Yii::$app->homeUrl . "';

        $('.field-company-com_latitude').hide();
        $('.field-company-com_longitude').hide();

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
