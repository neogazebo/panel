<h2 class="page-header">
	<img src="<?= Yii::$app->homeUrl ?>themes/AdminLTE/dist/img/ebz_logo.png" class="image-logo" alt="" width="64" height="56" />
	Snap &amp; Earn Report
	<small class="pull-right">
		<?= $first_date ?> to <?= $last_date ?>
	</small>
</h2>
<div class="col-sm-4">
	<address>
		<p class="text-muted well well-sm no-shadow" style="margin-top: 10px">
			<strong>Username: </strong> <?= $username ?><br />
			<strong>Country: </strong> <?= $country ?>
		</p>
	</address>
</div>
<table class="col-xs-12 table table-striped">
	<thead>
		<tr>
			<th>Type</th>
			<th>Start</th>
			<th>End</th>
			<th>Time Spent</th>
			<th>Description</th>
			<th>Point Type</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($report as $rp): ?>
		<tr>
			<td><?= $rp->wrk_type == 1 ? 'Snap & Earn' : 'Merchant' ?></td>
			<td><?= Yii::$app->formatter->asDateTime($rp->wrk_start) ?></td>
			<td><?= Yii::$app->formatter->asDateTime($rp->wrk_end) ?></td>
			<td><?= Yii::$app->formatter->asDateTime($rp->wrk_time) ?></td>
			<td><?= $rp->wrk_description ?></td>
			<td><?= $rp->wrk_description == 1 ? 'Update' : 'Correction' ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
