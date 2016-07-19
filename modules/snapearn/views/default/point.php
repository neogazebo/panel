<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use app\models\Company;

$form = ActiveForm::begin([
    'id' => 'create-form',
    'options' => ['class' => ''],
    'enableAjaxValidation' => true,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"\">{input}\n<div>{error}</div></div>",
        'labelOptions' => ['class' => ' control-label'],
    ]
]);
?>
<div class="modal-body">
    <?= $form->field($model, 'com_point')->textInput(['class' => 'form-control']) ?>
</div>
<div class="modal-footer">
    <div type="reset" class="pull-left btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</div>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'pull-right btn btn-info pull-right']) ?>
</div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJs("
    $('.modal-title').text('Add Point Merchant');
");
?>
