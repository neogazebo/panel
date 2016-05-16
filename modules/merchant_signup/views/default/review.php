<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model_merchant_signup app\model_merchant_signups\MerchantSignup */

$this->title = 'Review Merchant Signup: ' . $model_merchant_signup->mer_bussines_name;
$this->params['breadcrumbs'][] = ['label' => 'Merchant Signups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_merchant_signup->id, 'url' => ['view', 'id' => $model_merchant_signup->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="merchant-signup-update">

    <?= $this->render('_form', [
        'model_merchant_signup' => $model_merchant_signup,
        'model_company' => $model_company,
    ]) ?>

</div>
