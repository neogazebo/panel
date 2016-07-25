<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'create-form',
    'options' => ['class' => 'form-group disabled'],
]);
?>

<div class="modal-body">
    <?= $form->field($model, 'acc_cty_id')->dropDownList(['MY' => 'Malaysia', 'ID' => 'Indonesia'])->label('Select Country'); ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'btn btn-info pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
<?php
$this->registerJs("
$('.modal-title').text('Change Country');
");
?>
