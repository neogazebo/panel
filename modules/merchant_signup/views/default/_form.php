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

                <?= $form->field($model_merchant_signup, 'mer_bussines_type_retail')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_type_service')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_type_franchise')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_type_pro_services')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'mer_address')->textarea(['rows' => 6]) ?>

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
