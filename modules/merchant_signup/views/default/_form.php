<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

                <?php //echo $form->field($model_company, 'com_name')->textInput() ?>

                <?php //echo $form->field($model_company, 'com_email')->textInput(['data-content'=>'Your email for log in & reset password']); ?>

                <?= $form->field($model_merchant_signup, 'mer_bussines_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_company_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_bussiness_description')->textarea(['rows' => 6]) ?>

                <?= $form->field($model_company, 'com_subcategory_id')->dropDownList($model_company->categoryListData); ?>

                <?= $form->field($model_company, 'com_in_mall')->checkBox(['style' => 'margin-top:10px;'], false)->label('In Mall?') ?>

                <?=
                        $form->field($model_company, 'com_city')->widget(kartik\widgets\Typeahead::classname(), [
                            'options' => ['placeholder' => 'City, Region, Country', 'id' => 'location'],
                            'pluginOptions' => ['highlight' => true],
                            'dataset' => [
                                [
                                    'remote' => yii\helpers\Url::to(['/city/list']) . '?q=%QUERY',
                                    'limit' => 10
                                ]
                            ],
                            'pluginEvents' => [
                                'typeahead:selected' => 'function(evt,data) {}',
                            ]
                        ])->hint(Html::a(Html::img(Yii::$app->homeUrl . 'img/btn-plus.png', ['data-action' => 'destination', 'class' => 'find-address-book'])));
                        ?>

                <?= $form->field($model_company, 'com_size')->dropDownList($model_company->companySizeListData); ?>
                <?= $form->field($model_company, 'com_nbrs_employees')->dropDownList($model_company->numberEmployeeListData); ?>
                <?= $form->field($model_company, 'com_fb')->textInput(); ?>
                <?= $form->field($model_company, 'com_twitter')->textInput(); ?>
                <?= $form->field($model_company, 'com_timezone')->dropDownList($model_company->timeZoneListData) ?>
                <?= $form->field($model_company, 'com_reg_num')->textInput() ?>

                <div class="form-group">
                            <?= Html::activeLabel($model_company, 'fes_id', ['class' => 'col-lg-3 control-label']) ?>
                            <div class="col-lg-8">
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

                <?= $form->field($model_merchant_signup, 'mer_agent_code')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model_merchant_signup, 'mer_applicant_acknowledge')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'created_date')->textInput() ?>

                <?= $form->field($model_merchant_signup, 'updated_date')->textInput() ?>

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
    $(document).ready(function () {
        var baseUrl = '" . Yii::$app->homeUrl . "';
        loadRegister('EBC');
    });

    function loadRegister(reg)
    {
        $('select#company-fes_id').empty();
        $.ajax({
            type: 'GET',
            url: baseUrl + 'business/register',
            data: { reg: reg },
            success: function(result) {
                var comfes = $('select#company-fes_id');
                comfes.empty();
                comfes.append(result);
            }
        });
    }
", \yii\web\View::POS_END, 'business-create');
?>