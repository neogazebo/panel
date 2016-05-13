<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'List User "' . $name . '" in Role';
$dataProvider->sort->attributes['user.username'] = [
    'asc' => ['user.username' => SORT_ASC],
    'desc' => ['user.username' => SORT_DESC],
];
?>
<section class="content-header ">
    <h1><?= $this->title?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?= Html::a('<i class="fa fa-chevron-left"></i> Back', ['cancel'], ['class' => 'btn btn-success btn-sm']) ?>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <?= 
                        GridView::widget([
                            'id' => 'list-user-in-role',
                            'layout' => '{items} {summary} {pager}',
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'item_name',
                                'user.username',
                                [
                                    'attribute' => 'created_at',
                                    'value' => function($data) {
                                        return Yii::$app->formatter->asDateTime($data->created_at);
                                    }
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
