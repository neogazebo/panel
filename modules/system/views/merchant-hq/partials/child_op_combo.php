<div style="height: 200px; overflow:auto;">
	<p>You are about to add and/or remove these merchants:</p>

	<br>

	<p>Added Merchants:</p>

	<?php if($added_merchants): ?>
		<?php $merchants = ''; ?>
		<?php foreach($added_merchants as $added): ?>
			<?php $merchants .= $added . ', '; ?>
		<?php endforeach; ?>
		<strong><?= rtrim($merchants, ', ') ?></strong>
	<?php else: ?>
		<p>None</p>
	<?php endif; ?>

	<br />
	<br />

	<p>Removed Merchants:</p>

	<?php if($removed_merchants): ?>
		<?php $rmerchants = ''; ?>
		<?php foreach($removed_merchants as $removed): ?>
			<?php $rmerchants .= $removed . ', '; ?>
		<?php endforeach; ?>
		<strong><?= rtrim($rmerchants, ', ') ?></strong>
	<?php else: ?>
		<p>None</p>
	<?php endif; ?>
</div>
