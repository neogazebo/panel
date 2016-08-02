<pre>
	<?= var_dump($report); exit; ?>
</pre>
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
			<th>Activity</th>
			<th>Total Time</th>
			<th>Total Activity</th>
			<th>Point</th>
			<th>Total Point</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($report as $rp): ?>
		<tr>
			<td><?= $rp['ACTIVITY'] ?></td>
			<td><?= date('H:i:s', $rp['TOTAL_TIME']) ?></td>
			<td><?= Yii::$app->formatter->asDecimal($rp['TOTAL'], 0) ?></td>
			<td><?= Yii::$app->formatter->asDecimal($rp['POINT'], 0) ?></td>
			<td><?= Yii::$app->formatter->asDecimal($rp['TOTAL_POINT'], 0) ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
