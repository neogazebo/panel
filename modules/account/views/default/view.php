<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$this->title = $model->acc_screen_name;
$model->acc_gender = ($model->acc_gender == 1) ? 'Male' : 'Female';
$this->params['breadcrumbs'][] = ['label' => 'Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- <div class="account-view">

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

</div> -->

<section class="content">

          <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="<?= (!empty($model->acc_photo)) ? Yii::$app->params['memberUrl'].$model->acc_photo : $this->theme->baseUrl.'/dist/img/manis.png'?>" alt="<?= $model->acc_screen_name ?>">
                  <h3 class="profile-username text-center"><?= $model->acc_screen_name ?></h3>
                  <p class="text-muted text-center"><?= date('Y') - date('Y', strtotime($model->acc_birthdate)).' , '.$model->acc_gender  ?></p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Status </b> <a class="pull-right"><?= ($model->acc_status == 1) ? 'Verified' : '' ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Country </b> <a class="pull-right"><?= ($model->acc_cty_id == 'MY') ? 'Malaysia' : 'Indonesia' ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Friends</b> <a class="pull-right">13,287</a>
                    </li>
                  </ul>

                  <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                </div>
              </div>
            </div>
        </div>
</section>