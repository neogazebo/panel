<body style="font-family: 'Source Sans Pro';">
<div class="col-sm-4">
	<address>
		<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;margin-bottom: 20px;font-style: normal;line-height: 1.42857143;">
			<strong>Username: </strong> <?= $username ?><br />
			<strong>Period: </strong> <?= $first_date ?> to <?= $last_date ?>
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
			<td><?= date('Y M d H:m:s', $rp['wrk_start']) ?></td>
			<td><?= date('Y M d H:m:s', $rp['wrk_end']) ?></td>
			<td><?= date('H:i:s', $rp['wrk_time']) ?></td>
			<td><?= $rp['wrk_point'] ?></td>
			<td>
				<?php
				if ($rp['wrk_type'] == 2) {
                        echo ($rp['wrk_rjct_number'] != 0) ? '<p class="text-yellow">' . $rp['reason']['spo_name'] . '</p>' : 0;
                    } else{
                        echo ($rp['wrk_point'] == 4) ? '<p class="text-green">Approved <strong class="text-red">+</strong> </p>' : '<p class="text-green">Approved</p>';
                    }
				?>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
</body>
