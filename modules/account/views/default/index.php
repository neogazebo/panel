<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header ">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border"></div><!-- /.box-header -->
                <div class="box-body">

                    <form class="form-inline" action="account/default/export" method="get">
                        <div class="margin-bottom-10">
                            <div class="form-group">
                                <label></label><br>
                                <button name="output_type" value="excel" type="submit" class="btn btn-primary btn-flat"><i class="fa fa-file-excel-o"></i> Export to Excel</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'layout' => '{items} {summary} {pager}',
                            'filterModel' => $searchModel,
                            'columns' => [
                                [
                                    'label' => 'Full Name',
                                    'attribute' => 'acc_screen_name'
                                ],
                                [
                                    'label' => 'Email',
                                    'attribute' => 'acc_facebook_email'
                                ],
                                [
                                    'label' => 'Facebook Id',
                                    'attribute' => 'acc_facebook_id'
                                ],
                                [
                                    'label' => 'Registered Date',
                                    'attribute' => 'acc_created_datetime',
                                    'value' => function($data) {
                                       return Yii::$app->formatter->asDate($data->acc_created_datetime);
                                    }
                                ],
                                [
                                    'label' => 'Current Point',
                                    'attribute' => 'acc_id',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data->lastPointMember());
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{summary}</span>',
                                    'buttons' => [
                                        'summary' => function($url, $model) {
                                            return Html::a('<i class="fa fa-search"></i>', ['view', 'id' => $model->acc_id]);
                                        }
                                    ],
                                ],
                            ],
                            'tableOptions' => ['class' => 'table table-striped table-hover']
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
