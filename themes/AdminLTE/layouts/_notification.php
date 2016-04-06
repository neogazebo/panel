<?php $this->registerCss('.alert { margin-bottom: 0; margin: 14px; }') ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <?php if ($flash = Yii::$app->session->getFlash('success')): ?>
            <div class="alert alert-dismissable alert-success">
                <?= Yii::$app->session->getFlash('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
        <?php endif; ?>
        <?php if ($flash = Yii::$app->session->getFlash('error')): ?>
            <div class="alert alert-dismissable alert-danger">
                <?= Yii::$app->session->getFlash('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
        <?php endif; ?>
        <?php if ($flash = Yii::$app->session->getFlash('info')): ?>
            <div class="alert alert-dismissable alert-info">
                <?= Yii::$app->session->getFlash('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
        <?php endif; ?>
        <?php if ($flash = Yii::$app->session->getFlash('warning')): ?>
            <div class="alert alert-dismissable alert-warning">
                <?= Yii::$app->session->getFlash('warning'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
        <?php endif; ?>        
    </div>
</div>
