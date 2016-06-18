<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\WorkingTIme */

$this->title = $model->wrk_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Working Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-time-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->wrk_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->wrk_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'wrk_id',
            'wrk_type',
            'wrk_by',
            'wrk_param_id',
            'wrk_start',
            'wrk_end',
            'wrk_time',
            'wrk_description',
            'wrk_created',
            'wrk_updated',
        ],
    ]) ?>

</div>
