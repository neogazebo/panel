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

$url = Url::to(['merchant/default/list']);
$com_id = Yii::$app->request->getQueryParam('com_id');
$initScript = <<< SCRIPT
    function (element, callback) {
        var id = "{$com_id}";
        if (id !== "") {
            \$.ajax("{$url}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;
?>
<div class="modal-body">
	<?= Select2::widget([
        'name' => 'business',
        'options' => ['placeholder' => 'Choose a Business ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new yii\web\JsExpression("function(term,page) { return {search:term}; }"),
                'results' => new yii\web\JsExpression('function(data,page) { return {results:data.results}; }'),
            ],
            'initSelection' => new yii\web\JsExpression($initScript)
        ],
    ]);
    ?>
    <?= $form->field($model, 'sna_id')->hiddenInput()->label('') ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'pull-right btn btn-info pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
