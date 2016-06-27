<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Company;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;

$this->registerJsFile('https://maps.google.com/maps/api/js?sensor=true', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/gmaps/gmaps.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerCssFile($this->theme->baseUrl . '/dist/plugins/jQueryui/jquery-ui.min.css');
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

                <?= $form->field($model_company, 'com_size')->dropDownList($model_company->companySizeListData); ?>
                <?= $form->field($model_company, 'com_nbrs_employees')->dropDownList($model_company->numberEmployeeListData); ?>
                <?= $form->field($model_company, 'com_fb')->textInput(); ?>
                <?= $form->field($model_company, 'com_twitter')->textInput(); ?>
                <?= $form->field($model_company, 'com_timezone')->dropDownList($model_company->timeZoneListData) ?>
                <?= $form->field($model_company, 'com_reg_num')->textInput() ?>

                <div class="form-group field-merchantsignup-fes">
                    <label class="control-label" for="merchantsignup-fes">Package</label>
                    <div class="col-sm-12">
                        <select name="Company[fes_id]" id="company-fes_id" class="form-control"></select>
                    </div>
                </div>
                <?= $form->field($model_company, 'com_sales_id')->widget(kartik\widgets\Select2::classname(), [
                    'data' => yii\helpers\ArrayHelper::map(app\models\AdminUser::find()
                        ->where('type = :type', [':type' => 4])
                        ->all(), 'id', 'username'),
                    'options' => [
                        'placeholder' => 'Choose a Sales ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                <?= $form->field($model_company, 'com_sales_order')->textInput(['class' => 'form-control datepicker']) ?>

                <div class="box">
                    <div class="box-header"><strong>Business Type: </strong></div>
                    <div class="box-body">
                        <?= $form->field($model_merchant_signup, 'mer_bussines_type_retail')->checkBox() ?>

                        <?= $form->field($model_merchant_signup, 'mer_bussines_type_service')->checkBox() ?>

                        <?= $form->field($model_merchant_signup, 'mer_bussines_type_franchise')->checkBox() ?>

                        <?= $form->field($model_merchant_signup, 'mer_bussines_type_pro_services')->checkBox() ?>
                    </div>
                </div>


                <?= $form->field($model_merchant_signup, 'mer_post_code')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_office_phone')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_office_fax')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_website')->textInput(['maxlength' => true]) ?>

                <div class="form-group field-merchantsignup-mer_multichain">
                    <label class="control-label" for="merchantsignup-mer_multichain">Multichain</label>
                    <?php echo ($model_merchant_signup->mer_multichain) ? 'Yes' : 'No';  ?>
                    <?php echo ($model_merchant_signup->mer_multichain) ? '. The File: '.'<a href="'.Yii::$app->params['awsUrl'].'images/media/web/business/'.$model_merchant_signup->mer_multichain_file.'">'.$model_merchant_signup->mer_multichain_file.'</a>' : '';  ?>
                </div>

                <?php //$form->field($model_merchant_signup, 'mer_multichain_file')->textarea(['rows' => 6]) ?>

                <?= $form->field($model_merchant_signup, 'mer_login_email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_pic_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_contact_phone')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_contact_mobile')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_contact_email')->textInput(['maxlength' => true]) ?>

                <div class="box">
                    <div class="box-header"><strong>Preferred Communication: </strong></div>
                    <div class="box-body">
                    <?= $form->field($model_merchant_signup, 'mer_preferr_comm_mail')->checkBox() ?>

                    <?= $form->field($model_merchant_signup, 'mer_preferr_comm_email')->checkBox() ?>

                    <?= $form->field($model_merchant_signup, 'mer_preferr_comm_mobile_phone')->checkBox() ?>
                    </div>
                </div>

                <div class="form-group field-merchantsignup-com_photo">
                    <label class="control-label" for="merchantsignup-com_photo">Photo</label>
                    
                        <input type="hidden" id="com_photo" name="Company[com_photo]">
                        <a data-toggle="modal" data-image="com_logo" data-field="com_photo" href="#" class="eb-cropper">
                            <?php $image = isset($model_company->com_photo) ? Yii::$app->params['businessUrl'] . $model_company->com_photo : Yii::$app->params['imageUrl'] . 'default-image.jpg' ?>
                            <img src="<?= $image ?>" id="com_logo" class="img-responsive" width="240">
                        </a>
                    <div class="help-block"></div>
                </div>
                <div class="form-group field-merchantsignup-com_banner">
                    <label class="control-label" for="merchantsignup-com_banner">Banner</label>
                    
                        <input type="hidden" id="com_banner" name="Company[com_banner_photo]">
                        <a data-toggle="modal" data-image="com_banner_photo" data-field="com_banner" href="#" class="eb-cropper">
                            <?php $image = isset($model_company->com_banner_photo) ? Yii::$app->params['businessUrl'] . $model_company->com_banner_photo : Yii::$app->params['imageUrl'] . 'default-image.jpg' ?>
                            <img src="<?= $image ?>" id="com_banner_photo" class="img-responsive" width="240">
                        </a>
                        <div class="help-block"></div>
                </div>

                <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <?= Html::submitButton($model_merchant_signup->isNewRecord ? 'Create' : 'Copy to Company', ['class' => $model_merchant_signup->isNewRecord ? 'btn btn-success' : 'btn btn-primary pull-right', 'disabled' => true]) ?>
                                
                                <button type="reset" class="pull-left btn" onclick="window.location = '<?= Yii::$app->urlManager->createUrl('/') ?>'"><i class="fa fa-times"></i> Cancel</button>
                            </div>
                        </div>
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

        $('.datepicker').datepicker({
          autoclose: true
        });

        initialize();

        //triger modal for image-croper
        $('.eb-cropper').on('click',function(){
            $('#cropper-modal').modal({show: true});
        });

        loadRegister('EBC');

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
