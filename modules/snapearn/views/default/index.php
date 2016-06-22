<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use app\components\helpers\Utc;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Snap & Earn List';

$search = !empty(Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
$visible = Yii::$app->user->identity->superuser == 1 ? true : false;
?>
<section class="content-header ">
    <h1><?= $this->title?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <form class="form-inline" role="form" method="get" action="/snapearn">
                        <div class="form-group">
                        <label>Country</label>
                        <select name="sna_cty" class="form-control select2" style="width: 100%;">
                              <option value="" <?= (!empty($_GET['sna_cty']) == '' || empty($_GET['sna_cty'])) ? 'selected' : '' ?>>All</option>
                              <option value="ID" <?= (!empty($_GET['sna_cty']) && $_GET['sna_cty'] == 'ID') ? 'selected' : '' ?>>Indonesia</option>
                              <option value="MY" <?= (!empty($_GET['sna_cty']) && $_GET['sna_cty'] == 'MY') ? 'selected' : '' ?>>Malaysia</option>
                        </select>
                        </div>
                        <div class="form-group">
                        <label>Receipt Status</label>
                        <select name="sna_status" class="form-control select2" style="width: 100%;">
                              <option value="" <?= (!empty($_GET['sna_status']) && $_GET['sna_status'] == '' || empty($_GET['sna_status'])) ? 'selected' : '' ?>>All</option>
                              <option value="NEW" <?= (!empty($_GET['sna_status']) && $_GET['sna_status'] == 'NEW') ? 'selected' : '' ?>>New</option>
                              <option value="APP" <?= (!empty($_GET['sna_status']) && $_GET['sna_status'] == 'APP') ? 'selected' : '' ?>>Approved</option>
                              <option value="REJ" <?= (!empty($_GET['sna_status']) && $_GET['sna_status']== 'REJ') ? 'selected' : '' ?>>Rejected</option>
                        </select>
                        </div>
                        <!-- <div class="form-group">
                        <label>Join Status</label>
                        <select name="sna_join" class="form-control select2" style="width: 100%;">
                              <option value="" <?php // (!empty($_GET['sna_join']) == '' || empty($_GET['sna_join'])) ? 'selected' : '' ?>>All</option>
                              <option value="1" <?php // ($_GET['sna_join'] == '1') ? 'selected' : '' ?>>Joined</option>
                              <option value="2" <?php // ($_GET['sna_join'] == '2') ? 'selected' : '' ?>>UnJoined</option>
                        </select>
                        </div> -->
                        <div class="form-group">
                            <label>Date range</label><br>
                            <div class="input-group">
                                <div class="input-group-addon" for="reservation">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="sna_daterange" class="form-control pull-right" id="the_daterange" value="<?= (!empty($_GET['sna_daterange'])) ? $_GET['sna_daterange'] : '' ?>">
                            </div>
                        </div>
                        <?php if ($visible) : ?>
                        <div class="form-group">
                            <label for="member">Member</label><br>
                            <input name="sna_member" class="form-control" id="member" placeholder="Enter name" type="text" value="<?= (!empty($_GET['sna_member'])) ? $_GET['sna_member'] : '' ?>">
                        </div>  
                        <?php endif; ?>  
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                        </div>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'list_snapearn',
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                // [
                                //     'label' => 'Receipt',
                                //     'attribute' => 'sna_receipt_image',
                                //     'format' => 'raw',
                                //     'value' => function($data) {
                                //         return Html::img($data->image, ['style' => 'max-width: 70px; height: 32px']);
                                //     }
                                // ],
                                [
                                    'label' => 'Merchant',
                                    'attribute' => 'sna_com_id',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if (!empty($data->merchant)) {
                                            return $data->merchant['com_name'] . ($data->merchant['com_joined'] == 1 ? ' <i class="fa fa-check"></i>' : '');
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Member',
                                    'visible' => $visible,
                                    'attribute' => 'sna_acc_id',
                                    'value' => function($data) {
                                        return $data->member->acc_screen_name;
                                    }
                                ],
                                'sna_receipt_number',
                                'sna_receipt_date',
                                [
                                    'attribute' => 'sna_receipt_amount',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data->sna_receipt_amount);
                                    }
                                ],
                                [
                                    'attribute' => 'sna_point',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data->sna_point);
                                    }
                                ],
                                [
                                    'attribute' => 'sna_upload_date',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime(Utc::convert($data->sna_upload_date));
                                    }
                                ],
                                [
                                    'label' => 'Date Review',
                                    'attribute' => 'sna_approved_datetime',
                                    'value' => function($data) {
                                        if (!empty($data->sna_approved_datetime)) {
                                            return Yii::$app->formatter->asDateTime(Utc::convert($data->sna_approved_datetime));
                                        } elseif (!empty($data->sna_rejected_datetime)) {
                                            return Yii::$app->formatter->asDateTime(Utc::convert($data->sna_rejected_datetime));
                                        }
                                        
                                    }
                                ],
                                [
                                    'label' => 'Operator',
                                    'attribute' => 'sna_status',
                                    'value' => function($data) {
                                        if (!empty($data->adminRejected['username'])) {
                                            return $data->adminRejected['username'];
                                        } elseif (!empty($data->adminApproved['username'])) {
                                            return $data->adminApproved['username'];
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Status',
                                    'attribute' => 'sna_status',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if ($data->sna_status == 1) {
                                            return "<i class='fa fa-check approved-status'></i>";
                                        } elseif ($data->sna_status == 2) {
                                            return "<i class='fa fa-close rejected-status'></i>";
                                        } else {
                                            return "New";
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Description',
                                    'attribute' => 'sna_sem_id',
                                    'value' => function($data){
                                        if (!empty($data->remark)) {
                                            return $data->remark->sem_remark;
                                        }
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{update} {corection}</span>',
                                    'buttons' => [
                                        'update' => function($url,$model) {
                                            $superuser = Yii::$app->user->identity->superuser;
                                            if ($model->sna_status == 0) {
                                                return Html::a('<i class="fa fa-pencil-square-o"></i>', ['to-update', 'id' => $model->sna_id]);
                                            } elseif($model->sna_status != 0 && $superuser == 1) {
                                                return Html::a('<i class="fa fa-pencil-square-o btn-correction"></i>',['correction/to-correction','id' => $model->sna_id]);
                                            }
                                        },
                                    ],
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]);
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerJs("
    // $('#filtersearch').popover();
    // $('#filtersearch').on('keypress', function(ev) {
    //     if(ev.which == 13) {
    //         window.location = baseUrl + 'snapearn/default?search=' + encodeURIComponent($(this).val());
    //     }
    // });
", yii\web\View::POS_END, 'snapearn-list');
?>
