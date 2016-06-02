<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$this->title = $model->acc_id;
$this->params['breadcrumbs'][] = ['label' => 'Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->acc_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->acc_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'acc_id',
            'acc_facebook_id',
            'acc_facebook_email:email',
            'acc_facebook_graph:ntext',
            'acc_google_id',
            'acc_google_email:email',
            'acc_google_token',
            'acc_screen_name',
            'acc_cty_id',
            'acc_photo',
            'acc_created_datetime:datetime',
            'acc_updated_datetime:datetime',
            'acc_status',
            'acc_tmz_id',
            'acc_birthdate',
            'acc_address',
            'acc_gender',
        ],
    ]) ?>

</div>
