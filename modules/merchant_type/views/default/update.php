<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyType */

$update_title = 'Update Company Type: ' . $model->com_type_name;?>
<div id="edit-type-<?= $model->com_type_id; ?>" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><?= Html::encode($update_title) ?></h4>
      </div>
	    <?= $this->render('_form', [
	        'model' => $model,
	    ]) ?>
    </div>
  </div>
</div>
