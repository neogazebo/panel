<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$this->title = 'Working Hours Report for ' . $user->username;
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools"></div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'form-report',
                        'options' => ['class' => 'form-horizontal', 'target'=>''],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-8\">{input}\n<div>{error}</div></div>",
                            'labelOptions' => ['class' => 'col-lg-2 control-label'],
                        ],
                    ]);
                    ?>

                    <div class="form-group field-pdfform-daterange">
                        <label class="col-lg-2 control-label">Date range</label>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <div class="input-group-addon" for="report">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="PdfForm[date_range]" class="form-control" id="the_daterange" value="<?= (!empty(Yii::$app->request->get('daterange'))) ? Yii::$app->request->get('daterange') : '' ?>" style="width: 200px">
                            </div>
                        </div>
                    </div>
                    <?= $form->field($model, 'download_is')->checkBox(['style' => 'margin-top: 10px'])->label('Download?') ?>
                    <?= $form->field($model, 'order')->dropDownList(['DESC' => 'DESC', 'ASC' => 'ASC']) ?>
                    <?= $form->field($model, 'username')->hiddenInput(['style' => 'margin: 0'])->label('') ?>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" class="pull-right btn-primary btn btn-submitBuy"><i class="fa fa-check"></i> Save</button>
                                <button type="reset" class="pull-left btn" onclick="window.location = '<?= Yii::$app->urlManager->createUrl('logwork/default/cancel?id=' . $user->id) ?>'"><i class="fa fa-times"></i> Cancel</button>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>

<?php
$this->registerJs("
    $('.datepicker').datepicker();
", yii\web\View::POS_LOAD, 'working-hours-report');
?>
