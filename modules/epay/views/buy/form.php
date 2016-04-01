<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'New Epay Voucher' : 'Edit Voucher Epay';
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                    <div class="box-tools">

                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'form-reward',
                        'options' => ['class' => 'form-horizontal', 'target'=>''],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
                            'labelOptions' => ['class' => 'col-lg-2 control-label'],
                        ],
                    ]);
                    ?>

                    <?= $form->field($model, 'epa_vou_id')->dropDownList(ArrayHelper::map($model->voucher, 'vou_id', 'vou_reward_name'))?>
                    <?= $form->field($model, 'epa_epp_id')->dropDownList(ArrayHelper::map($model->product, 'epp_id', 'epp_title'))?>
                    <?= $form->field($model, 'epa_qty')->textInput(); ?>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="pull-right btn-primary btn"><i class="fa fa-check"></i> Save</button>
                                <button type="reset" class="pull-left btn" onclick="window.location = '<?= Yii::$app->urlManager->createUrl('epay/buy') ?>'"><i class="fa fa-times"></i> Cancel</button>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>
