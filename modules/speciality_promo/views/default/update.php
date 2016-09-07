<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ComSpecialityPromo */

$this->title = 'Update Com Speciality Promo: ' . $model->spt_promo_id;
$this->params['breadcrumbs'][] = ['label' => 'Com Speciality Promos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->spt_promo_id, 'url' => ['view', 'id' => $model->spt_promo_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="com-speciality-promo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
