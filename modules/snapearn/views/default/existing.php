
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
        <form id="existing_merchant_form" action="/snapearn/default/ajax-existing?id=<?= $model->sna_id ?>" method="post">
        <div class="modal-body">
            <div class="form-group">
                <input type="text" id="com_name_search" name="com_name" class="form-control" value="" width="200px" placeholder="Enter merchant name" />
                <input type="hidden" name="url" value="<?= Url::current() ?>">
                <input type="hidden" id="com_id" name="sna_com_id" value="">
                <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken(); ?>">
            </div>
        </div>
        <div class="modal-footer">
            <?= Html::resetButton('<i class="fa fa-times"></i> Cancel', ['class' => 'pull-left btn btn-warning', 'data-dismiss' => 'modal']) ?>
            <?= Html::submitButton('<i class="fa fa-check"></i> Submit', ['class' => ' btn btn-info']) ?>
        </div>
       </form>
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
