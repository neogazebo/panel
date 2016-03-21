<?php

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'New Epay Voucher' : 'Edit Voucher Epay';
?>

<div id='wrap'>
    <div class="container">
        <div id="page-heading">
            <h1><i class="fa fa-qrcode"></i> <?= Yii::t('app', $this->title) ?></h1>
        </div>

        <div class="row" style="padding: 10px">
            <div class="col-md-12">
                <div class="row">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4><i class="fa fa-qrcode"></i> <?= Yii::t('app', $this->title); ?></h4>
                        </div>
                        <div class="panel-body" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'form-reward',
                                'options' => ['class' => 'form-horizontal','target'=>''],
                                'fieldConfig' => [
                                    'template' => "{label}\n<div class=\"col-lg-6\">{input}\n<div>{error}</div></div>",
                                    'labelOptions' => ['class' => 'col-lg-3 control-label'],
                                ],
                            ]);
                            ?>

                            <?= $form->field($model, 'epa_vou_id')->dropDownList(ArrayHelper::map($model->voucher, 'vou_id', 'vou_reward_name'))?>
                            <?= $form->field($model, 'epa_epp_id')->dropDownList(ArrayHelper::map($model->product, 'epp_id', 'epp_title'))?>
                            <?= $form->field($model, 'epa_qty')->textInput(); ?>

                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="pull-right btn-primary btn"><i class="fa fa-check"></i> Save</button>
                                        <button type="reset" class="pull-left btn" onclick="window.location = '<?= Yii::$app->urlManager->createUrl('epay/buy') ?>'"><i class="fa fa-times"></i> Cancel</button>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>