<?php

use app\components\helpers\GlobalHelper;
use app\models\CompanyType;
use app\models\Country;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanySpeciality */
/* @var $form yii\widgets\ActiveForm */
$country = new GlobalHelper();
?>
    <?php $form = ActiveForm::begin([
        'id' => $model->isNewRecord ? 'promo-speciality' : 'promo-speciality-'.$model->com_spt_id,
        'action' => $model->isNewRecord ? '/speciality/default/create' : '/speciality/default/update?id='.$model->com_spt_id,
    ]); ?>
    <?php
        $point = (floatval($model->com_spt_multiple_point) == 0) ? '' : floatval($model->com_spt_multiple_point);
    ?>
    <div class="modal-body">

        <?= $form->field($model, 'com_spt_type_id')->dropDownList(ArrayHelper::map(CompanyType::find()->all(), 'com_type_id', 'com_type_name'), ['prompt'=>'Choose...'])->label('Company Speciality'); ?>

    	<?= $form->field($model, 'com_spt_cty_id')->dropDownList(ArrayHelper::map(Country::find()->all(), 'cty_id', 'cty_name'), ['prompt'=>'Choose...'])->label('Select Country...'); ?>

        <?= $form->field($model, 'com_spt_multiple_point')->textInput([
            'value' => $point,
            'placeholder' => 'ex : 1.5'
            ]) ?>
        <?= $form->field($model, 'com_spt_max_point')->textInput() ?>

    </div>
    <div class="clearfix"></div>
    <div class="modal-footer">
        <?= Html::resetButton('<i class="fa fa-times"></i> Back', ['class' => 'btn btn-default pull-left','onclick' => 'formReset()']) ?>
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check"></i> Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
<?php ActiveForm::end(); ?>
