<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MerchantSignupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Merchant Signups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merchant-signup-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Merchant Signup', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'mer_bussines_name',
            'mer_company_name',
            'mer_bussiness_description:ntext',
            'mer_bussines_type_retail',
            // 'mer_bussines_type_service',
            // 'mer_bussines_type_franchise',
            // 'mer_bussines_type_pro_services',
            // 'mer_address:ntext',
            // 'mer_post_code',
            // 'mer_office_phone',
            // 'mer_office_fax',
            // 'mer_website',
            // 'mer_multichain',
            // 'mer_multichain_file:ntext',
            // 'mer_login_email:email',
            // 'mer_pic_name',
            // 'mer_contact_phone',
            // 'mer_contact_mobile',
            // 'mer_contact_email:email',
            // 'mer_preferr_comm_mail',
            // 'mer_preferr_comm_email:email',
            // 'mer_preferr_comm_mobile_phone',
            // 'mer_agent_code',
            // 'mer_applicant_acknowledge',
            // 'created_date',
            // 'updated_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
