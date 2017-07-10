<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ComSpecialityPromo */

$this->title = $model->spt_promo_id;
$this->params['breadcrumbs'][] = ['label' => 'Com Speciality Promos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="com-speciality-promo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->spt_promo_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->spt_promo_id], [
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
            'spt_promo_id',
            'spt_promo_com_spt_id',
            'spt_promo_description',
            'spt_promo_multiple_point',
            'spt_promo_created_by',
            'spt_promo_start_date',
            'spt_promo_end_date',
            'spt_promo_created_date',
        ],
    ]) ?>

</div>
