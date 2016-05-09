<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\modal;
// use yii\helpers\BaseUrl;
use yii\helpers\Url;

$this->title = 'List Role';
?>
<section class="content-header ">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::button('<i class="fa fa-plus-square"></i> Create Role',['value' => Url::to(['create']),'class' => 'modalButton btn btn-primary btn-sm']) ?>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'id' => 'ListRole',
                            'layout' => '{items}{summary}{pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'name',
                                'description',
                                [
                                    'attribute' => 'created_by',
                                    'format' => 'html',
                                    'value' => function($data){
                                        if(!empty($data->user))
                                            return $data->user->username;
                                    }
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'value' => function($data){
                                        return Yii::$app->formatter->asDateTime($data->created_at);
                                    }
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'value' => function($data){
                                        $create = Yii::$app->formatter->asDateTime($data->created_at);
                                        $update = Yii::$app->formatter->asDateTime($data->updated_at);
                                        if($create!== $update)
                                            return Yii::$app->formatter->asDateTime($data->updated_at);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '<span class="pull-right actionColumn">  {view} &nbsp; {update} &nbsp; {delete} &nbsp;</span>',
                                    'buttons' => [
                                        'view' => function($url, $model){
                                            return Html::a('<i class="fa fa-search"></i>', ['detail?name=' . $model->name]);
                                        },
                                        'update' => function($url,$model){
                                            return Html::button('<i class="fa fa-pencil-square-o"></i>', ['value' => Url::to(['update?name='.$model->name]),'class' => 'modalButton']);
                                        },
                                        'delete' => function($url,$model){
                                            return Html::button('<i class="fa fa-times-circle-o"></i>', ['value'=>Url::to(['delete?name=' . $model->name]),'class' => 'deleteBtn']);
                                        }
                                    ],
                                ],
                            ]
                        ]);
                        ?>



                <!-- widget to create render modal -->
                <?php
                    Modal::begin([
                            'header' => '<h2>Create Role</h2>',
                            'id' => 'modal',
                            'size' => 'modal-md',
                        ]);
                    echo "<div id='modalContent'></div>";
                    Modal::end();
                ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>