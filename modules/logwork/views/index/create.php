<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WorkingTIme */

$this->title = Yii::t('app', 'Create Working Time');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Working Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-time-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
