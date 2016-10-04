<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MerchantSignup */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Merchant Signups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->title = 'Detail Merchant';

$arr_bis_type = [];
($model->mer_bussines_type_retail) ? $arr_bis_type[] = 'Retail' : '';
($model->mer_bussines_type_service) ? $arr_bis_type[] = 'Service' : '';
($model->mer_bussines_type_franchise) ? $arr_bis_type[] = 'Franchise' : '';
($model->mer_bussines_type_pro_services) ? $arr_bis_type[] = 'Professional Services' : '';

$types = [
    'food' => 'Food',
    'fashion' => 'Fashion',
    'entertainment' => 'Entertainment',
    'tech_gadget' => 'Tech & Gadgets',
    'event' => 'Events',
    'home_living' => 'Home & Living',
    'health_beauty' => 'Health & Beauty',
    'travel' => 'Travel',
    'shopping' => 'Shopping',
    'sport' => 'Sports',
    'film_music' => 'Film & Music',
    'business' => 'Business',
];

foreach ($types as $key => $type) {
    $obj_name = "mer_bussines_type_{$key}";
    ($model->$obj_name) ? $arr_bis_type[] = $type : '';
}

/*'mer_multichain',
'mer_multichain_file:ntext',*/

$arr_prefer_contact = [];
($model->mer_preferr_comm_mail) ? $arr_prefer_contact[] = 'Mail' : '';
($model->mer_preferr_comm_email) ? $arr_prefer_contact[] = 'E-Mail' : '';
($model->mer_preferr_comm_mobile_phone) ? $arr_prefer_contact[] = 'Mobile Phone' : '';

$multichain = ($model->mer_multichain) ? '<a href="'.Yii::$app->params['awsUrl'].'images/media/web/business/'.$model->mer_multichain_file.'">'.$model->mer_multichain_file.'</a>' : '';
//$multichain = '<a href="https://d1307f5mo71yg9.cloudfront.net/images/media/web/business/rsz_img-1457059048_56d8f4e8ca8f9.jpg">dfsdf</a>';
?>
<section class="content-header">
    <h1><?= $this->title ?></h1>
</section>

<div class="merchant-signup-view">
<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-body">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?php
            //echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);

            /*
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                    'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
            */
        ?>

        <button type="reset" class="pull-left btn" onclick="window.location = '<?= Yii::$app->urlManager->createUrl('merchant-signup') ?>'"><i class="fa fa-times"></i> Back</button>
        <br style="clear: both">
    </p>

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'mer_bussines_name',
                                'mer_company_name',
                                'mer_bussiness_description:ntext',
                                [
                                    'label' => 'Business Type',
                                    'value' => implode(', ', $arr_bis_type)
                                ],
                                'mer_address:ntext',
                                'mer_post_code',
                                'mer_office_phone',
                                'mer_office_fax',
                                'mer_website',
                                [
                                    'label' => 'Multichain',
                                    'format' => 'html',
                                    'value' => $multichain
                                ],
                                'mer_unifi_id',
                                'mer_login_email:email',
                                'mer_pic_name',
                                'mer_contact_phone',
                                'mer_contact_mobile',
                                'mer_contact_email:email',
                                [
                                    'label' => 'Prefer contact',
                                    'value' => implode(', ', $arr_prefer_contact)
                                ],
                            ],
                        ]) ?>


                </div>

            </div>
        </div>
    </div>
</section>
</div>
