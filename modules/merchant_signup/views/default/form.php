<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'New Merchant' : 'Edit Merchant';
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'form-merchant',
                        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
                            'labelOptions' => ['class' => 'col-lg-2 control-label'],
                        ],
                    ]);
                    ?>

                    <?= $form->field($model, 'mer_bussines_name')->textInput(); ?>
                    <?= $form->field($model, 'mer_company_name')->textInput(); ?>
                    <?= $form->field($model, 'mer_bussiness_description')->textArea(); ?>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Business Type</label>
                        <div class="col-lg-8">
                            <div class="col-lg-3">
                                <?= $form->field($model, 'mer_bussines_type_retail')->checkBox(); ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'mer_bussines_type_service')->checkBox(); ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'mer_bussines_type_franchise')->checkBox(); ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'mer_bussines_type_pro_services')->checkBox(); ?>
                            </div>
                        </div>
                    </div>
                    <?= $form->field($model, 'mer_address')->textArea(); ?>
                    <?= $form->field($model, 'mer_post_code')->textInput(); ?>
                    <?= $form->field($model, 'mer_office_phone')->textInput(); ?>
                    <?= $form->field($model, 'mer_office_fax')->textInput(); ?>
                    <?= $form->field($model, 'mer_website')->textInput(); ?>
                    <?= $form->field($model, 'mer_multichain')->checkBox()->label(''); ?>
                    <?= $form->field($model, 'mer_login_email')->textInput(); ?>
                    <?= $form->field($model, 'mer_multichain_file')->fileInput(); ?>
                    <?= $form->field($model, 'mer_pic_name')->textInput(); ?>
                    <?= $form->field($model, 'mer_contact_phone')->textInput(); ?>
                    <?= $form->field($model, 'mer_contact_mobile')->textInput(); ?>
                    <?= $form->field($model, 'mer_contact_email')->textInput(); ?>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Preferred Communication Method</label>
                        <div class="col-lg-8">
                            <div class="col-lg-3">
                                <?= $form->field($model, 'mer_preferr_comm_mail')->checkBox(); ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'mer_preferr_comm_email')->checkBox(); ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'mer_prefer_office_phone')->checkBox(); ?>
                            </div>
                            <div class="col-lg-3">
                                <?= $form->field($model, 'mer_preferr_comm_mobile_phone')->checkBox(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="pull-right btn-primary btn"><i class="fa fa-check"></i> Save</button>
                                <button type="reset" class="pull-left btn" onclick="window.location = '<?= Yii::$app->urlManager->createUrl('merchant-signup/default/cancel') ?>'"><i class="fa fa-times"></i> Cancel</button>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>
