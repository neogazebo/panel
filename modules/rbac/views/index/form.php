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
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'description')->textArea() ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'disabled btn btn-info pull-right']) ?>
</div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJs("
	$('#authitem-name').blur(function(){
		if ($('.help-block').text() == '') {
			$('button.pull-right').removeClass('disabled');
		} else {
			$('button.pull-right').addClass('disabled');
		}
	});
");
?>