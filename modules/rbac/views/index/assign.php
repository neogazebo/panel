<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;

$form = ActiveForm::begin([
    'id' => 'create-form',
    'options' => ['class' => 'form-group'],
    'enableClientValidation'=>true,
    'enableAjaxValidation' => true,
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ]
]);

?>
<div class="modal-body">
	<?= 
        Typeahead::widget([
            'name' => 'itemName',
            'options' => ['placeholder' => 'Filter as you type ...'],
            'pluginOptions' => [
                'highlight'=>true,
                'minLength' => 3
            ],
            'pluginEvents' => [
                "typeahead:select" => "function(ev, suggestion) { $('#authassignment-user_id').val(suggestion.id); }",
            ],
            'dataset' => [
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('username')",
                    'display' => 'username',
                    'remote' => [
                        'url' => Url::to(['user-list']) . '?q=%QUERY&&role='.$role,
                        'wildcard' => '%QUERY'
                    ],
                    'limit' => 20
                ]
            ]
        ]);
    ?>

    <?= $form->field($model, 'user_id')->hiddenInput()->label('') ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'pull-right btn btn-info pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>
<?php
$this->registerJs("
    $('.modal-title').text('Assign User');
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=='text'))  {return false;}
    }

    document.onkeypress = stopRKey; 
");
?>
