<?php

$this->title = 'Permissions';

$this->registerCssFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/js/plugins/waitme/waitMe.css');
$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'common/js/plugins/waitme/waitMe.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);
$this->registerJsFile(Yii::$app->urlManager->createAbsoluteUrl('') . 'pages/PermissionManager.js', ['depends' => app\themes\AdminLTE\assets\AppAsset::className()]);

?>

<section class="content-header "> 
    <h1><?= $this->title?></h1>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <div class="box-tools">

                    </div>
                </div><!-- /.box-header -->

                <div class="box-body">

                    <div class="panel-body">
                            
                        <div class="form-group">
                            <div class="row">
                                <label class="col-lg-3 control-label" for="company-com_currency">Modules</label>

                                <div class="col-lg-8">
                                    <select class="form-control module-selector">
                                        <option value="">Select Module</option>
                                        <?php foreach($module_names as $module_name): ?>
                                            <option value="<?= $module_name; ?>"><?= $module_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                        </div>

                        <div class="table-responsive resource-wrapper"></div>

                    </div>
                </div>
			</div>
		</div>
	</div>
</section>