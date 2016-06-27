<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\modal;
use yii\helpers\Url;

$this->title = 'User Management';
$search = !empty(Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::a('<i class="fa fa-plus-square"></i> New User', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'ListRole',
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                'username',
                                'email',
                                [
                                    'label' => 'Role',
                                    'value' => function($data) {
                                        if(!empty($data->authAssignment))
                                            return $data->authAssignment->item_name;
                                    }
                                ],
                                [
                                    'label' => 'Registered Since',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime($data->create_time);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{view}</span>',
                                    'buttons' => [
                                        'view' => function($url, $model) {
                                            return Html::a('<i class="fa fa-search"></i>', ['detail?id=' . $model->id]);
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
    $('#filtersearch').popover();
    $('#filtersearch').on('keypress', function(ev) {
        if(ev.which == 13) {
            window.location = baseUrl + 'users/index?search=' + encodeURIComponent($(this).val());
        }
    });
", yii\web\View::POS_END, 'user-' . time());
?>
