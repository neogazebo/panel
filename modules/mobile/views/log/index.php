<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Mobile Pulsa Topup Log';
$dataProvider->sort->defaultOrder = ['mpt_datetime' => SORT_DESC];
$dataProvider->sort->attributes['partner.red_name'] = [
    'asc' => ['partner.red_name' => SORT_ASC],
    'desc' => ['partner.red_name' => SORT_DESC],
];
$dataProvider->sort->attributes['member.mem_screen_name'] = [
    'asc' => ['member.mem_screen_name' => SORT_ASC],
    'desc' => ['member.mem_screen_name' => SORT_DESC],
];
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?php echo Html::a('<i class="fa fa-retweet"></i>  <span>' . Yii::t('app', 'Syncronize Mobile Pulsa Server') . '</span>', ['sync'], ['class' => 'btn btn-primary']) ?>  
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <?=
                            GridView::widget([
                                'id' => 'mp-topup',
                                'layout' => '{items}{summary}{pager}',
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                    	'attribute' => 'partner.red_name',
                                    	'value' => function($data) {
                                    		if(!empty($data->partner))
                                    			return $data->partner->red_name;
                                    	}
                                    ],
                                    [
                                    	'attribute' => 'member.mem_screen_name',
                                    	'value' => function($data) {
                                    		if(!empty($data->member))
                                    			return $data->member->mem_screen_name;
                                    	}
                                    ],
	                                [
	                                    'attribute' => 'mpt_datetime',
	                                    'format' => 'html',
	                                    'value' => function($data) {
	                                        return Yii::$app->formatter->asDatetime($data->mpt_datetime, "php:d M Y H:i:s");
	                                    }
	                                ], 
	                                'mpt_product_code',
	                                'mpt_msisdn',
	                                'mpt_ref_id',
	                                [
	                                    'attribute' => 'mpt_status',
	                                    'format' => 'html',
	                                    'value' => function($data) {
	                                        return $data->mpt_status == 0 ? 'Success' : 'Failed';
	                                    }
	                                ],                                                                                   
	                                'mpt_message',
                                ],
                                'tableOptions' => ['class' => 'table table-striped']
                            ]);
                            ?>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
