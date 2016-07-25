<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'create-form',
    'options' => ['class' => 'form-group disabled'],
    'enableAjaxValidation' => true
]);
?>

<div class="modal-body">
    <?= $form->field($model, 'lph_member')->textInput(['value' => is_object($model->member) ? $model->member->acc_screen_name : '', 'readonly' => true]); ?>
    <?= $form->field($model, 'lph_acc_id')->hiddenInput()->label('', ['style' => 'display: none']) ?>
    <?= $form->field($model, 'lph_amount')->textInput(['value' => 0]); ?>
    <?= $form->field($model, 'lph_type')->dropDownList(['C' => 'Credit', 'D' => 'Debet']); ?>
    <?= $form->field($model, 'lph_description')->textArea(); ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'btn btn-info pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
<?php
$this->registerJs("
$('.modal-title').text('Correction Point');
");
?>
