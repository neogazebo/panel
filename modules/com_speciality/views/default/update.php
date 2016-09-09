<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanySpeciality */

$this->title = 'Update Company Speciality: ' . $model->com_spt_id;
$this->params['breadcrumbs'][] = ['label' => 'Company Specialities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->com_spt_id, 'url' => ['view', 'id' => $model->com_spt_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="company-speciality-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
