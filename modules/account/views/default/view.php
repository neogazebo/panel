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
    
    .profile-user-img.img-responsive.img-circle {
        max-height: 100px;
        max-width: 100px;
        overflow: hidden;
    }

");
?>

<section class="content">

          <div class="row">
            <div class="col-md-3">
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="<?= (!empty($model->acc_photo)) ? Yii::$app->params['memberUrl'].$model->acc_photo : $this->theme->baseUrl.'/dist/img/manis.png'?>" alt="<?= $model->acc_screen_name ?>">
                  <h3 class="profile-username text-center"><?= $model->acc_screen_name ?></h3>
                  <p class="text-muted text-center"><?= (!empty($model->acc_birthdate)) ? date('Y') - date('Y', $model->acc_birthdate) .' , ' : ' ' ?> <?= $model->acc_gender  ?></p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Country </b> <a class="pull-right"><?= ($model->acc_cty_id == 'MY') ? 'Malaysia' : 'Indonesia' ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Current Point </b> <a class="pull-right"><?= $model->lastPointMember() ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Last Activity </b> <a class="pull-right"><?= Yii::$app->formatter->asDate($model->lastLogin()) ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Facebook Id </b> <a href="https://www.facebook.com/<?= $model->acc_facebook_id ?>" target="blank_" class="pull-right"> <?= $model->acc_facebook_id ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Registered Since  </b> <a class="pull-right"><?= Yii::$app->formatter->asDate($model->acc_created_datetime) ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Device Active  </b> <a class="pull-right"><?= $model->activeDevice()->dvc_model ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>OS Version  </b> <a class="pull-right"><?= $model->activeDevice()->dvc_os_version ?></a>
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
                          <h3 class="timeline-header">
                            <a href="#">Last Location</a>
                          </h3>
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