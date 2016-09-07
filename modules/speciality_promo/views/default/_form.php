<?php

use app\models\CompanySpeciality;
// use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ComSpecialityPromo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="com-speciality-promo-form">

    <?php $form = ActiveForm::begin([
        'id' => 'promo',
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
    ]); ?>
    <?=
    $form->field($model, 'spt_promo_com_spt_id')->dropDownList(ArrayHelper::map(CompanySpeciality::find()->all(), 'com_spt_id', 'com_spt_merchant_speciality_name'), ['prompt'=>'Choose...'])->label('Company Speciality'); ?>
    <?= $form->field($model, 'spt_promo_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'spt_promo_point')->textInput() ?>
    <!-- <div class="col-sm-6"> -->
        <?=
            DatePicker::widget([
                'model' => $model,
                'attribute' => 'spt_promo_start_date',
                //'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
            ]);
        ?>
   <!--  </div>
    <div class="col-sm-6"> -->
        <?=
            DatePicker::widget([
                'model' => $model,
                'attribute' => 'spt_promo_end_date',
                //'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
            ]);
        ?>
    <!-- </div> -->
  
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
