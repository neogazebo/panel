<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use yii\helpers\ArrayHelper;
use app\components\helpers\Utc;

$this->title = 'Detail Working Hours';
?>
<section class="content-header ">
    <h1><?= Html::encode($this->title).' of <strong>'.$username ?></strong></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?php // Html::a('<i class="fa fa-share"></i> Report', ['report', 'id' => $id], ['class' => 'pull-left btn btn-primary']) ?>
                    <div class="col-sm-6">
                       
                    </div>
                    <div class="col-sm-6">
                        <form class="form-inline pull-right" role="form" method="post" action="view?id=<?= $_GET['id'] ?>">
                        <div class="form-group">
                            <label>Date range</label><br>
                            <div class="input-group">
                                <div class="input-group-addon" for="reservation">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="wrk_daterange" class="form-control pull-right" id="the_daterange" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Submit</button>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                [
                                    'label' => 'Date',
                                    'attribute' => 'wrk_end',
                                    'value' => function($data) {
                                        date_default_timezone_set('UTC');
                                        return date('Y M d H:m:s',$data->wrk_end);
                                    }
                                ],
                                [
                                    'label' => 'Description',
                                    'attribute' => 'wrk_rjct_number',
                                    'format' => 'html',
                                    'value' => function($data){
                                        if ($data->wrk_type == 2) {
                                            return ($data->wrk_rjct_number != 0) ? '<p class="text-yellow">'.$data->reason->sem_remark.'</p>' : '<a class=""><span class="not-set">(not set)</span></a>';
                                        } else{
                                            return '<p class="text-green">Approved</p>';
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Total Point',
                                    'attribute' => 'wrk_point'
                                ],
                                [
                                    'label' => 'Record Activity',
                                    'attribute' => 'wrk_time',
                                    'value' => function($data) {
                                        date_default_timezone_set('UTC');
                                        return date('H:i:s', $data->wrk_time);
                                    }
                                ]
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
