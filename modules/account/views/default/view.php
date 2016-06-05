<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$this->title = $model->acc_screen_name;
$model->acc_gender = ($model->acc_gender == 1) ? 'Male' : 'Female';
$this->params['breadcrumbs'][] = ['label' => 'Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss("
    #gmap-waypoints {
        position: relative;
        display: block;
        width: 100%;
    }
    .margin {
        margin: 10px;
        position: relative;
        width: 200px;
    }
");
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
                      <b>Last Point </b> <a class="pull-right"><?= $model->lastPointMember() ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Last Login </b> <a class="pull-right"><?= Yii::$app->formatter->asDate($model->lastLogin()) ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Facebook Link </b> <a href="https://www.facebook.com/<?= $model->acc_facebook_id ?>" target="blank_" class="pull-right"> Link</a>
                    </li>
                    <li class="list-group-item">
                      <b>Registered From  </b> <a class="pull-right"><?= Yii::$app->formatter->asDate($model->acc_created_datetime) ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Device Active  </b> <a class="pull-right"><?= $model->activeDevice() ?></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class="nav-tabs-custom box-primary">
                <ul class="nav nav-tabs">
                  <li class=""><a aria-expanded="false" href="#activity" data-toggle="tab">Activity</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="activity">
                    <!-- The timeline -->
                    <ul class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      <?php foreach ($model->lastLocation() as $location): ?>
                      <li class="time-label">
                        <span class="bg-red">
                          Timeline
                        </span>
                      </li>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <li>
                        <i class="fa fa-map-marker bg-blue"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asDateTime($location->adv_last_access) ?></span>
                          <h3 class="timeline-header"><a href="#">Last Location</a></h3>
                          <div class="timeline-body">
                            <?=
                                \app\components\widgets\GmapLocation::widget([
                                    'lat' => $location->adv_last_latitude,
                                    'long' => $location->adv_last_longitude,
                                    'height' => 150,
                                    'type' => 'static'
                                ]);
                                ?>
                          </div>
                          <div class="timeline-footer">
                          <!--   <a class="btn btn-primary btn-xs">Read more</a>
                            <a class="btn btn-danger btn-xs">Delete</a> -->
                          </div>
                        </div>
                      </li>
                      <?php endforeach; ?>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                 <!--      <li>
                        <i class="fa fa-user bg-aqua"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>
                          <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>
                        </div>
                      </li> -->
                      <!-- END timeline item -->
                      <!-- timeline item -->
                     <!--  <li>
                        <i class="fa fa-comments bg-yellow"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>
                          <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
                          <div class="timeline-body">
                            Take me to your leader!
                            Switzerland is small and neutral!
                            We are more like Germany, ambitious and misunderstood!
                          </div>
                          <div class="timeline-footer">
                            <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                          </div>
                        </div>
                      </li> -->
                      <li>
                        <i class="fa fa-camera bg-purple"></i>
                        <div class="timeline-item">
                        <?php foreach ($model->lastSnapUpload() as $upload) : ?>
                          <span class="time"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asDateTime($upload->sna_upload_date) ?></span>
                          <h3 class="timeline-header"><a href="#">Last Uploaded Receipt</a></h3>
                          <div class="timeline-body">
                            <div class="container">
                              <div class="row col-sm-3">
                                <img src="<?= Yii::$app->params['businessUrl'] . 'receipt/' . $upload->sna_receipt_image ?>" alt="..." class="margin">
                              </div>
                              <div class="col-sm-9">
                                  <b>Status : </b> 
                                  <a class="">
                                      <?= ((!empty($upload->sna_approved_datetime)) ? 'Approved' : (!empty($upload->sna_rejected_datetime)) ? 'Rejected' : 'New') ?>
                                  </a>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                        </div>
                      </li>
                      <!-- END timeline item -->
                      <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                      </li>
                    </ul>
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
        </div>
</section>