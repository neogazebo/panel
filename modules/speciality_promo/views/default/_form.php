<?php

use app\components\helpers\GlobalHelper;
use app\models\CompanySpeciality;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$country = new GlobalHelper;
$speciality = CompanySpeciality::find()->with('type','country')->asArray()->all();
$get_type = [];
foreach ($speciality as $val) {
    $get_type[$val['com_spt_id']] = $val['type']['com_type_name'].' ('.$val['country']['cty_name'].')';
}

?>
    <?php $form = ActiveForm::begin([
        'id' => $model->isNewRecord ? 'promo-speciality' : 'promo-speciality-'.$model->spt_promo_id,
        'action' => $model->isNewRecord ? '/promo/default/create' : '/promo/default/update?id='.$model->spt_promo_id,
    ]); ?>
    <div class="modal-body">
        <?= $form->field($model, 'spt_promo_com_spt_id')->dropDownList($get_type, ['prompt' => 'Choose...'])->label('Company Speciality'); ?>
        <?= $form->field($model, 'spt_promo_description')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'spt_promo_day_promo')->dropDownList(GlobalHelper::Weekdays(), ['prompt' => 'Special Day...']) ?>
        <?= $form->field($model, 'spt_promo_multiple_point')->textInput() ?>
        <div class="col-sm-6" style="padding: 0px 12px 0px 0px;">
            <?=
            $form->field($model, 'spt_promo_start_date')->widget(DatePicker::classname(),[ 
                'options' => [
                    'id' => 'start_'.$model->spt_promo_id,
                ],
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
                'options' => [
                    'id' => 'end_'.$model->spt_promo_id,
                ],
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
        <?= Html::resetButton('<i class="fa fa-times"></i> Back', ['class' => 'btn btn-default pull-left','onclick' => 'formReset()']) ?>
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> Create' : 'i.fa.fa-check Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>

<?php ActiveForm::end(); ?>

