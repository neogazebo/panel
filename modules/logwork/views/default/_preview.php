<?php
$total_time = 0;
$total_activities = 0;
$total_point = 0;
?>
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
			<th style="text-align: center;">Total Time</th>
			<th style="text-align: right;">Total Activity</th>
			<th style="text-align: right;">Point</th>
			<th style="text-align: right;">Total Point</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($report as $rp): ?>
		<tr>
			<td><?= $rp['ACTIVITY'] ?></td>
			<td style="text-align: center;"><?= date('H:i:s', $rp['TOTAL_TIME']); $total_time += $rp['TOTAL_TIME']; ?></td>
			<td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($rp['TOTAL'], 0); $total_activities += $rp['TOTAL']; ?></td>
			<td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($rp['POINT'], 0) ?></td>
			<td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($rp['TOTAL_POINT'], 0); $total_point += $rp['TOTAL_POINT']; ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<td style="font-weight: bold;">TOTAL</td>
			<td style="text-align: center;"><?= date('H:i:s', $total_time); ?></td>
			<td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($total_activities, 0); ?></td>
			<td style="text-align: right;">&nbsp;</td>
			<td style="text-align: right;"><?= Yii::$app->formatter->asDecimal($total_point, 0); ?></td>
		</tr>
	</tfoot>
</table>
