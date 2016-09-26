<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\helpers\Utc;

$this->title = 'Role List';
?>
<section class="content-header "> 
    <h1><?= $this->title?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?=
                    Html::button('<i class="fa fa-plus-square"></i> New Role', [
                        'value' => Url::to(['create']),
                        'class' => 'modalButton btn btn-primary btn-sm'
                    ]);
                    ?>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'ListRole',
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                [
                                    'label' => 'Role Name',
                                    'attribute' => 'name',
                                    'value' => function($data){
                                        if (!empty($data->name)) {
                                            return $data->name;
                                        }
                                    }
                                ],
                                [
                                    'label' => 'Description',
                                    'value' => function($data){
                                        if (!empty($data->description)) 
                                            return $data->description;
                                    }
                                ],
                                [
                                    'label' => 'Created By',
                                    'format' => 'html',
                                    'value' => function($data) {
                                        if(!empty($data->user))
                                            return $data->user->username;
                                    }
                                ],
                                [
                                    'label' => 'Created Date',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime(Utc::convert($data->created_at));
                                    }
                                ],
                                [
                                    'label' => 'Updated Date',
                                    'value' => function($data) {
                                        $create = Yii::$app->formatter->asDateTime($data->created_at);
                                        $update = Yii::$app->formatter->asDateTime($data->updated_at);
                                        if($create!== $update)
                                            return Yii::$app->formatter->asDateTime(Utc::convert($data->updated_at));
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">{user} {view} {update} <!--{delete}--></span>',
                                    'buttons' => [
                                        'user' => function($url, $model) {
                                            return Html::a('<i class="fa fa-group"></i>', ['user?name=' . urlencode($model->name)]);
                                        },
                                        'view' => function($url, $model) {
                                            return Html::a('<i class="fa fa-search"></i>', ['detail?name=' . urlencode($model->name)]);
                                        },
                                        'update' => function($url, $model) {
                                            return Html::button('<i class="fa fa-pencil-square-o"></i>', ['value' => Url::to(['update?name='.$model->name]), 'class' => 'modalButton']);
                                        },
                                        // 'delete' => function($url,$model) {
                                        //     return Html::button('<i class="fa fa-times-circle-o"></i>', ['value' => Url::to(['delete?name=' . $model->name]), 'class' => 'deleteBtn']);
                                        // }
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

<!-- widget to create render modal -->
<?php
    Modal::begin([
        'header' => '</button><h4 class="modal-title">Role</h4>',
        'id' => 'modal',
        'size' => 'modal-md',
    ]);
?>
<div id="modalContent"></div>
<?php Modal::end(); ?>
