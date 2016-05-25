
<div class="modal fade" id="cropper-modal">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 720px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?=$modal_title;?></h4>
            </div>
            <div class="modal-body" style="margin-bottom: 10px;">

                <div class="col-xs-12 col-sm-12 col-lg-12 no-padding" style="margin-top: 10px;margin-bottom: 10px;">
                    <form id="imageform" action="<?= Yii::$app->params['frontendUrl'] . 'cropper/upload'; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <input type="file" name="photoimg" id="photoimg" />
                        <span><small>(Maximum image size is 2MB, and supported format are : "jpg", "jpeg", "png", "gif", "bmp".)</small></span>
                        <br />
                        <span><small>Please ensure that the dimension of the image at least 120 x 180</small></span>
                        <input type="hidden" name="max_size" value="300" />                        
                        <input type="hidden" id="prefix" name="prefix" value="<?=$prefix?>" />
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->csrfToken?>" />
                    </form>
                </div>

                <table id="table-crop" cellpadding="0" cellspacing="1" class="table" style="width: 100%;">
                    <tbody>
                    <tr class="no-padding">
                        <td id="block-src" class="td-crop" align="center">
                            <img class="img-responsive image-loader" src="<?=Yii::$app->params['frontendUrl']?>img/loading_white.gif">
                            <img class="img-responsive image-to-crop center-block" src="">
                        </td>
                        <td id="block-dst" class="td-crop" align="center">
                            <img class="img-responsive image-loader" src="<?=Yii::$app->params['frontendUrl']?>img/loading_white.gif">
                            <img class="img-responsive image-result-crop center-block" src="">
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="col-xs-12 col-sm-6 col-lg-6 no-padding" style="margin-top: 10px;margin-bottom: 10px;">
                    <form id="form-crop" action="<?= Yii::$app->params['frontendUrl'] . 'cropper/process'; ?>" method="post" class="form-horizontal">
                        <input type="hidden" name="x" id="x" size="4"/>
                        <input type="hidden" name="y" id="y" size="4"/>
                        <input type="hidden" name="w" id="w" size="4"/>
                        <input type="hidden" name="h" id="h" size="4"/>
                        <input type="hidden" id="src-file" name="name" />
                        <input type="hidden" id="action" name="action" />
                        <input type="hidden" id="wsmall" name="wsmall" value="<?=$wsmall?>" />
                        <input type="hidden" id="hsmall" name="hsmall" value="<?=$hsmall?>" />
                        <input type="hidden" id="wbig" name="wbig" value="<?=$wbig?>" />
                        <input type="hidden" id="hbig" name="hbig" value="<?=$hbig?>" />
                        <input type="hidden" id="ratio" name="ratio" value="<?=$ratio?>" />
                        <input type="hidden" id="x-factor" name="xFactor" placeholder="x factor" value="" />
                        <input type="hidden" id="skip-and-resize" name="skip_and_resize" value="<?=$skipAndResize?>" />
                        <input type="hidden" id="create-thumbnail" name="create_thumbnail" value="<?=$createThumbnail?>" />
                        <a href="javascript:void(0)" class="btn btn-primary" id="crop-image">Crop</a>
                        <a href="javascript:void(0)" class="btn btn-primary" id="skip-crop">Skip</a>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="clear: both;">
                <div class="col-md-8 no-padding">
                    <div class="crop-loader" style="">
                        <img class="img-responsive pull-left" src="<?=Yii::$app->homeUrl?>img/loader.gif">
                        <span class="pull-left">Processing data, please wait ...</span>
                    </div>
                </div>
                <div class="col-md-4 no-padding">
                    <form id="form-publish-crop" action="<?= Yii::$app->params['frontendUrl'] . 'cropper/publish'; ?>" method="post" class="form-horizontal">
                        <input type="hidden" id="final-file" name="name" value="" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="save-button" type="button" class="btn btn-primary">Save changes</button>
                    </form>
                    <form id="form-clearance" action="<?= Yii::$app->params['frontendUrl'] . 'cropper/clear'; ?>" method="post" class="form-horizontal">
                    </form>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
//list($width, $height, $type, $attr) = getimagesize($urlTemp.$imageNew);
//echo $height.'>'.$width;
?>
<?php
$this->registerCss("
.no-padding{padding:0px;}
.image-to-crop{max-height: 300px; max-width: 300px;display:none;}
.image-result-crop{max-height: 300px; max-width: 300px;display:none;}
.crop-loader{margin-top: 9px;display:none;}
.crop-loader img{margin-right: 10px;}
.image-loader{margin:0 auto;width: 50%;display: none;}
table .td-crop{width: 50%;vertical-align: middle !important;border: 1px solid #ccc;}
#table-crop{display:none;}
");
?>
<?php $this->registerJs("var baseURL = '" . \yii\helpers\BaseUrl::base(true) . "';", \yii\web\View::POS_BEGIN, 'init-opt-begin'.time());?>
<?php $this->registerCssFile(Yii::$app->params['frontendUrl'] . 'themes/avant/css/jcrop/jquery.Jcrop.css');?>
<?php // $this->registerCssFile(Yii::$app->params['frontendUrl'] . 'themes/avant/css/styles.css');?>
<?php $this->registerJsFile(Yii::$app->params['frontendUrl'] . 'themes/avant/js/jcrop/jquery.Jcrop.js',['depends' => app\themes\avant\assets\AppAsset::className()]); ?>
<?php $this->registerJsFile(Yii::$app->params['frontendUrl'] . 'themes/avant/js/jquery.form.js',['depends' => app\themes\avant\assets\AppAsset::className()]); ?>
<?php $this->registerJsFile(Yii::$app->params['frontendUrl'] . 'themes/avant/js/custom/image-cropper.js',['depends' => app\themes\avant\assets\AppAsset::className()]); ?>