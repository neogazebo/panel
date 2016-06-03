<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\widgets\Select2;
use app\models\Company;

$form = ActiveForm::begin([
    'id' => 'create-form',
    'options' => ['class' => 'form-group'],
    'enableAjaxValidation' => true,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ]
]);
?>
<div class="modal-body">
	<?php
	$url = Url::to(['merchant/default/list']);
	// $business = empty($model->company->com_id) ? '' : Company::findOne($model->company->com_id)->com_name;
	echo Select2::widget([
        // 'initValueText' => $business, // set the initial display text
        'data' => $model,
	    'options' => ['placeholder' => 'Search for a merchant ...'],
	    'pluginOptions' => [
	        'allowClear' => true,
	        'minimumInputLength' => 3,
	        'language' => [
	            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
	        ],
	        'ajax' => [
	            'url' => $url,
	            'dataType' => 'json',
	            'data' => new JsExpression('function(params) { return { q: params.term }; }')
	        ],
	        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
	        'templateResult' => new JsExpression('function (business) { return business.text; }'),
	        'templateSelection' => new JsExpression('function (business) { return business.text; }'),
	    ],
    ]);
    ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'pull-right btn btn-info pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
