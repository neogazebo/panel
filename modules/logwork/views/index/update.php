<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WorkingTIme */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Working Time',
]) . $model->wrk_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Working Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->wrk_id, 'url' => ['view', 'id' => $model->wrk_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="working-time-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
