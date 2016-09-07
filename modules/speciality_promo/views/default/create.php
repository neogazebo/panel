<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ComSpecialityPromo */

$this->title = 'Create Com Speciality Promo';
$this->params['breadcrumbs'][] = ['label' => 'Com Speciality Promos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content-header ">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border"></div><!-- /.box-header -->
                <div class="box-body">
				    <?= $this->render('_form', [
				        'model' => $model,
				    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
