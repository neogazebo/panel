
 <?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Company;
$this->registerCssFile($this->theme->baseUrl.'/plugins/jQueryUI/jquery-ui.min.css');
$this->registerCssFile($this->theme->baseUrl.'/plugins/jQueryUI/jquery-ui.theme.min.css');
$this->registerJs("var search_mechant_url = '" . Url::to(['default/list-merchant-non-hq']) . "';", \yii\web\View::POS_BEGIN);

?>
        <?php
        $form = ActiveForm::begin([
            'id' => 'create-form',
            'action' => '/snapearn/default/ajax-existing?id='.$model->sna_id,
            'method' => 'post',
            'options' => [
                'class' => 'form-group',
                'data-pjax' => true
                ],
            'enableClientValidation'=>true,
            'enableAjaxValidation' => true,
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
                'labelOptions' => ['class' => 'col-lg-3 control-label'],
            ]
        ]);

        ?>
        <div class="modal-body">
            <div class="form-group">
                <input type="text" id="com_name_search" class="form-control" value="" width="200px" placeholder="Enter merchant name" />
                <input type="hidden" name="url" value="<?= Url::current() ?>">
                <?= $form->field($model,'sna_id')->fieldHidden() ?>
            </div>
        </div>
        <div class="modal-footer">
            <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
            <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => 'pull-right btn btn-info pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>
<?php
$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'pages/SnapEarnManager.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJs("
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=='text'))  {return false;}
    }

    document.onkeypress = stopRKey;
");
?>
