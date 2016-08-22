<div style="height: 200px; overflow:auto;">
	<p>You are about to add and/or remove these merchants:</p>

	<br>

	<p>Added Merchants:</p>

	<?php if($added_merchants): ?>
		<ol>
			<?php foreach($added_merchants as $added): ?>
				<li><strong><?= $added; ?></strong></li>
			<?php endforeach; ?>
		</ol>
	<?php else: ?>
		<p>None</p>
	<?php endif; ?>

	<br>

	<p>Removed Merchants:</p>

	<?php if($removed_merchants): ?>
		<ol>
			<?php foreach($removed_merchants as $removed): ?>
				<li><strong><?= $removed; ?></strong></li>
			<?php endforeach; ?>
		</ol>
	<?php else: ?>
		<p>None</p>
	<?php endif; ?>
</div>
