<?php

use app\components\helpers\GlobalHelper;
use app\models\CompanySpeciality;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$country = new GlobalHelper;
?>
    <?php $form = ActiveForm::begin([
        'id' => 'promo-speciality',
        'action' => $model->isNewRecord ? '/promo/default/create' : '/promo/default/update',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    ]); ?>
    <div class="modal-body">
        <?=
        $form->field($model, 'spt_promo_com_spt_id')->dropDownList(ArrayHelper::map(CompanySpeciality::find()->all(), 'com_spt_id', 'com_spt_type'), ['prompt'=>'Choose...'])->label('Company Speciality'); ?>
        <?= $form->field($model, 'spt_promo_cty_id')->dropDownList($country->TempCountry(),['prompt' => 'Select Country...']) ?>
        <?= $form->field($model, 'spt_promo_description')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'spt_promo_day_promo')->dropDownList(GlobalHelper::Weekdays(),['prompt' => 'Special Day...']) ?>
        <?= $form->field($model, 'spt_promo_multiple_point')->textInput() ?>
        <div class="col-sm-6" style="padding: 0px 12px 0px 0px;">
            <?=
            $form->field($model, 'spt_promo_start_date')->widget(DatePicker::classname(),[
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ],
            ]);
            ?>
        </div>
        <div class="col-sm-6" style="padding: 0px 0px 0px 12px;">
            <?=
            $form->field($model, 'spt_promo_end_date')->widget(DatePicker::classname(),[
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="modal-footer">
        <?= Html::button('Back',['class' => 'btn btn-default pull-left','data-dismiss' => 'modal']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>

<?php ActiveForm::end(); ?>

