<div style="height: 200px; overflow:auto;">
	<p>You are about to <?= $op; ?> these merchants:</p>

	<ol>
		<?php foreach($data as $d): ?>
			<li><strong><?= $d; ?></strong></li>
		<?php endforeach; ?>
	</ol>
</div>