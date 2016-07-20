<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Cash Voucher';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <form class="form-inline" action="cash" method="get">
                        <div class="form-group">
                        <label>Member</label>
                        <div>
                            <input type="text" class="form-control" name="member" value="<?= Yii::$app->request->get('member') ?>" />
                        </div>
                        </div>
                        <div class="form-group">
                            <label>Merchant</label>
                            <div>
                                <input type="text" class="form-control" name="merchant" value="<?= Yii::$app->request->get('merchant') ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Voucher</label>
                            <div>
                                <input type="text" class="form-control" name="voucher" value="<?= Yii::$app->request->get('voucher') ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Transaction Time</label>
                            <div>
                                <input type="text" class="form-control" name="update" id="the_daterange" value="<?= Yii::$app->request->get('update') ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                        </div>
                    </form>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'redemption-reference',
                            'options' => [
                                'style' => 'font-size: 13px'
                            ],
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'pjaxSettings' => [
                                'neverTimeout' => true,
                            ],
                            'columns' => [
                                'account.acc_screen_name',
                                [
                                    'attribute' => 'cvr_pvd_update_datetime',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->cvr_pvd_update_datetime));
                                    }
                                ],
                                'cvr_pvo_name',
                                'cvr_com_name',
                                'cvr_pvd_sn',
                                'cvr_pvd_code',
                                [
                                    'attribute' => 'cvr_pvd_expired',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDatetime(\app\components\helpers\Utc::convert($data->cvr_pvd_expired));
                                    }
                                ],
                            ],
                            'bordered' => false,
                            'striped' => false
                            // 'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]);
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
