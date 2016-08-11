<?php
use app\components\helpers\Utc;
?>

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
			<th>Point</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($report as $rp): ?>
		<tr>
			<td><?= $rp['wrk_type'] == 3 ? 'Add Merchant' : 'S&E Approval' ?></td>
                        <td>
                            <?= Yii::$app
                                ->formatter
                                ->asDatetime(Utc::convert(strtotime(date('Y-m-d H:i:s', $rp['wrk_start']))),'php:d-m-Y H:i:s') 
                            ?>
                        </td>
			<td>
                            <?= Yii::$app
                                ->formatter
                                ->asDatetime(Utc::convert(strtotime(date('Y-m-d H:i:s', $rp['wrk_end']))),'php:d-m-Y H:i:s') 
                            ?>
                        </td>
			<td><?= date('H:i:s', $rp['wrk_time']) ?></td>
			<td><?= $rp['spo_name'] ?></td>
                        <td><?= $rp['wrk_point'] ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
</body>
