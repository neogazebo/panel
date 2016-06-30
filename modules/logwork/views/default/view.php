<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Typeahead;
use kartik\widgets\TypeaheadBasic;
use yii\helpers\ArrayHelper;
use app\components\helpers\Utc;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Detail Working Hours';
?>
<section class="content-header ">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::a('<i class="fa fa-share"></i> Report', ['report', 'id' => $id], ['class' => 'pull-left btn btn-primary']) ?>
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
                                        return Yii::$app->formatter->asDateTime(Utc::convert($data->wrk_end));
                                    }
                                ],
                                [
                                    'label' => 'Username',
                                    'attribute' => 'wrk_by',
                                    'value' => function($data) {
                                        return $data->user->username;
                                    }
                                ],
                                [
                                    'label' => 'Description',
                                    'attribute' => 'wrk_description',
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