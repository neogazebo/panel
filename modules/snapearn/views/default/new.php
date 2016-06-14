<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;


$this->title = "New Merchant";
// echo Yii::$app->urlManager->hostInfo;exit;
?>

<section class="content-header">
    <h1><?= $this->title ?></h1>
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
    'id' => 'create-form',
    'options' => ['class' => 'form-group'],
    'enableAjaxValidation' => true,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ]
]);
?>
<div class="panel-body">
    <?= $form->field($company, 'com_name')->textInput(['value' => $suggest->cos_name]) ?>
    <?= $form->field($company, 'com_business_name')->textInput(['value' => $suggest->cos_name]) ?>
    <?= $form->field($company, 'com_email')->textInput() ?>
    <?= $form->field($company, 'com_subcategory_id')->dropDownList($company->categoryList); ?>
    <?= $form->field($company, 'com_in_mall')->checkBox(['style' => 'margin-top:10px;'])->label('In Mall?') ?>
   
    <?= 
        $form->field($company, 'com_city')->widget(Typeahead::classname(),[
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
    <input id="com_city" type="hidden" name="com_city" value="">
    <?= $form->field($company, 'com_postcode')->textInput(); ?>
    <?= $form->field($company, 'com_address')->textInput(); ?>
    <?= 
        $form->field($company, 'mall_name')->widget(Typeahead::classname(),[
            'name' => 'merchant',
            'options' => [
                'placeholder' => 'Mall Name',
                'value' => $suggest->cos_mall
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
        ])->label('Select Mall');
    ?>
    <?= $form->field($company, 'mall_id')->hiddenInput(['value' => $suggest->cos_mall_id])->label('') ?>
    <?= $form->field($company, 'com_mac_id')->dropDownList([]) ?>
    <div class="form-group" id="businessMap">
        <label class="col-lg-3 control-label">Map</label>
        <div class="col-lg-8">
            <div id="map" style="height:300px"></div>
        </div>
    </div>
    <div id="floor-unit" class="form-group">
        <div class="form-group hide" id="hasmallkey">
            <label class="col-lg-3 control-label">&nbsp;</label>
            <div class="col-lg-8">
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
    <?= $form->field($company, 'com_phone')->textInput(); ?>
    <?= $form->field($company, 'com_fax')->textInput(); ?>
    <?= $form->field($company, 'com_website')->textInput(); ?>
    <?= $form->field($company, 'com_size')->dropDownList($company->companySizeListData); ?>
    <?= $form->field($company, 'com_nbrs_employees')->dropDownList($company->numberEmployeeListData); ?>
    <?= $form->field($company, 'com_fb')->textInput(); ?>
    <?= $form->field($company, 'com_twitter')->textInput(); ?>
    <?= $form->field($company, 'com_timezone')->dropDownList($company->timeZoneListData) ?>
    <?= $form->field($company, 'com_reg_num')->textInput() ?>
    <?= $form->field($company, 'com_gst_enabled')->checkBox(['style' => 'margin-top:10px;'], false)->label('Gst?') ?>
    <?= $form->field($company, 'com_gst_id')->textInput() ?>
    <?= $form->field($company, 'fes_id')->dropDownList(ArrayHelper::map(app\models\FeatureSubscription::find()->all(),'fes_code','fes_name')) ?>
    <?= $form->field($company, 'com_point')->textInput(); ?>
    <?= $form->field($company, 'com_latitude')->hiddenInput()->label('') ?>
    <?= $form->field($company, 'com_longitude')->hiddenInput()->label('') ?>

    <div class="form-group">
        <label class="col-sm-3 control-label">Photo</label>
        <div class="col-xs-8">
            <input type="hidden" id="com_photo" name="Company[com_photo]">
            <a data-toggle="modal" data-image="com_logo" data-field="com_photo" href="#" class="eb-cropper">
                <?php $image = isset($company->com_photo) ? Yii::$app->params['businessUrl'] . $company->com_photo : Yii::$app->params['imageUrl'] . 'default-image.jpg' ?>
                <img src="<?= $image ?>" id="com_logo" class="img-responsive thumbnail" width="240">
            </a>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Banner</label>
        <div class="col-xs-8">
            <input type="hidden" id="com_banner" name="Company[com_banner_photo]">
            <a data-toggle="modal" data-image="com_banner_photo" data-field="com_banner" href="#" class="eb-cropper">
                <?php $image = isset($company->com_banner_photo) ? Yii::$app->params['businessUrl'] . $company->com_banner_photo : Yii::$app->params['imageUrl'] . 'default-image.jpg' ?>
                <img src="<?= $image ?>" id="com_banner_photo" class="img-responsive thumbnail" width="240">
            </a>
        </div>
    </div>
    </div>
<div class="box-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning refreshParent', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'pull-right btn btn-info pull-right']) ?>
</div>
<?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- widget to create render modal -->
<?php
    Modal::begin([
        'header' => '</button><h4 class="modal-title"></h4>',
        'id' => 'modal',
        'size' => 'modal-md',
    ]);
?>
<div id="modalContent"></div>
<?php Modal::end(); ?>
<?= \app\components\widgets\ImageCropper::widget([
    'wsmall' => 200, // width small
    'hsmall' => 134, // height small
    'wbig' => 600, // width big
    'hbig' => 400, // height big
    'ratio' => 1.5, // ratio dimension crop box
    'skipAndResize' => true, // true or false, if false => original image will be duplicated, if true original image will be resized to wbig x hbig
    'prefix' => 'img-', // for file name prepix
]); ?>
<?php
$this->registerJsFile('https://maps.google.com/maps/api/js?sensor=true', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile($this->theme->baseUrl . '/plugins/gmaps/gmaps.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$latitude = ($company->com_latitude ? $company->com_latitude : 3.139003);
$longitude = ($company->com_longitude ? $company->com_longitude : 101.686855);
$this->registerCss("
    .datetimepicker-dropdown-bottom-right {
        right: 200px;
    }
");
$this->registerJs("
    //triger modal for image-croper
    $('.eb-cropper').on('click',function(){
        $('#cropper-modal').modal({show: true});
    });

    var mall_checked = 1;
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
                $(this).val(0);
                loadMap();
            }else{
                $(this).val(1);
            }
        });
    });
    // if($('.status').val() == 1) {
    //     $('.point-form').css('display', 'block');
    // } else if($('.status').val() == 2) {
    //     $('.reject-form').css('display', 'block');
    // }

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
        // $('#snapearn-sna_point').val(point);
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

    // $('.saveNext').click(function(){
    //     $('#saveNext').val(1);
    // });
    $('.submit-button, .reset-button').click(function(){
        $('#saveNext').val(0);
    });

function refreshOpenerTopFrameset(){
    var f = window.opener.top.frames;
    for (var i = f.length - 1; i > -1; --i)
    f[i].location.reload();
}

",yii\web\View::POS_END, 'snapearn-form');
?>
