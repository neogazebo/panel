<?php
$total_time = 0;
$total_activities = 0;
$total_point = 0;
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body style="font-family: 'Source Sans Pro';">
<div class="col-sm-4">
    <address>
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;margin-bottom: 20px;font-style: normal;line-height: 1.42857143;">
            <b style="font-weight: normal">Username : </b> <?= $username  ?><br>
            <b style="font-weight: normal">Period : </b> <?= $first_date ?> to <?= $last_date ?>
        </p>
    </address>
</div>

<table>
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
            <td><?= $rp['spo_name'] ?></td>
            <td><?= date('H:i:s', $rp['total_time']); $total_time += $rp['total_time']; ?></td>
            <td><?= Yii::$app->formatter->asDecimal($rp['activity'], 0); $total_activities += $rp['activity']; ?></td>
            <td><?= Yii::$app->formatter->asDecimal($rp['spo_point'], 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($rp['total_point'], 0); $total_point += $rp['total_point']; ?></td>
    </tr>
    <?php endforeach ?>
    </tbody>
    <tfoot style="border-top: 2px solid #777">
    <tr>
        <td style="font-weight: bold;">TOTAL</td>
        <td style="font-weight: bold;"><?= date('H:i:s', $total_time); ?></td>
        <td style="font-weight: bold;"><?= Yii::$app->formatter->asDecimal($total_activities, 0); ?></td>
        <td style="font-weight: bold;">&nbsp;</td>
        <td style="font-weight: bold;"><?= Yii::$app->formatter->asDecimal($total_point, 0); ?></td>
    </tr> 
     </tfoot>
</table>

</body>
</html>

