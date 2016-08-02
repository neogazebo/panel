<h2 class="page-header">
	<img src="<?= Yii::$app->homeUrl ?>themes/AdminLTE/dist/img/ebz_logo.png" class="image-logo" alt="" width="64" height="56" />
	Snap &amp; Earn Detail Report
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
			<td><?= $rp['wrk_type'] == 1 ? 'Snap & Earn' : 'Merchant' ?></td>
			<td><?= Yii::$app->formatter->asDatetime($rp['wrk_start']) ?></td>
			<td><?= Yii::$app->formatter->asDatetime($rp['wrk_end']) ?></td>
			<td><?= Yii::$app->formatter->asDatetime($rp['wrk_time']) ?></td>
			<td><?= $rp['wrk_point'] ?></td>
			<td>
				<?php
				if ($rp['wrk_type'] == 2) {
                        echo ($rp['wrk_rjct_number'] != 0) ? '<p class="text-yellow">' . $rp['reason']['sem_remark'] . '</p>' : 0;
                    } else{
                        echo ($rp['wrk_point'] == 4) ? '<p class="text-green">Approved <strong class="text-red">+</strong> </p>' : '<p class="text-green">Approved</p>';
                    }
				?>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
