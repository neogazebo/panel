<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CompanySpeciality */

$this->title = 'Create Company Speciality';
$this->params['breadcrumbs'][] = ['label' => 'Company Specialities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-speciality-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
