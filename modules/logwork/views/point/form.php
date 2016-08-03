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
    <?= $form->field($model, 'spo_name') ?>
    <?= $form->field($model, 'spo_point') ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Save', ['class' => 'disabled btn btn-info pull-right']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs("
	$('button.pull-right').removeClass('disabled');
	$('#authitem-name').blur(function() {
		if ($(this).val() == '') {
			$('button.pull-right').removeClass('disabled');
		} else {
			$('button.pull-right').addClass('disabled');
		}
	});
");
?>